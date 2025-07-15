<template>
    <a-modal
        :open="open"
        :title="'Evaluación de etapa'"
        @cancel="handleCancel"
        :footer="null"
        :centered="true"
    >
        <a-form layout="vertical" @submit.prevent="handleSubmit">
            <p class="my-5 text-gray-700">
                Al finalizar la etapa <b>{{ etapa?.nombre_etapa }}</b> el niño/a
                presenta:
            </p>
            <div class="flex gap-4">
                <a-form-item label="Peso (kg)" required class="flex-1">
                    <a-input-number
                        v-model:value="form.peso"
                        :min="0"
                        :max="100"
                        :step="0.01"
                        style="width: 100%"
                        placeholder="Ej. 12.5"
                    />
                </a-form-item>
                <a-form-item label="Talla (cm)" required class="flex-1">
                    <a-input-number
                        v-model:value="form.talla"
                        :min="0"
                        :max="200"
                        :step="0.1"
                        style="width: 100%"
                        placeholder="Ej. 85.0"
                    />
                </a-form-item>
            </div>
            <a-form-item label="Foto (opcional)">
                <div class="flex items-start gap-4">
                    <a-upload
                        list-type="picture-card"
                        :file-list="fileList"
                        :before-upload="beforeUpload"
                        :on-preview="handlePreview"
                        :on-remove="handleRemove"
                        :max-count="1"
                    >
                        <div v-if="fileList.length < 1">
                            <plus-outlined />
                            <div style="margin-top: 8px">Subir</div>
                        </div>
                    </a-upload>
                    <a-input
                        v-model:value="fotoDescripcion"
                        placeholder="Descripción de la foto (opcional)"
                        class="mt-2"
                        style="
                            min-width: 180px;
                            max-width: 260px;
                            align-self: flex-start;
                        "
                    />
                </div>
                <a-modal v-model:open="previewVisible" footer="null">
                    <img
                        :src="previewImage"
                        alt="Foto previsualización"
                        style="width: 100%"
                    />
                </a-modal>
            </a-form-item>
            <a-form-item
                label="Comentario o información adicional (opcional)"
                :help="'Mientras más información nos brinde del niño, mejor podremos evaluar su desarrollo.'"
            >
                <a-input.TextArea
                    v-model:value="form.comentario_madre"
                    placeholder="Ejm: Habla en frases cortas, camina sin ayuda, muestra curiosidad por explorar, etc."
                    :rows="3"
                />
            </a-form-item>
            <div class="flex justify-end gap-2 mt-6">
                <a-button @click="handleCancel">Cancelar</a-button>
                <a-button type="primary" html-type="submit" :loading="loading"
                    >Enviar</a-button
                >
            </div>
        </a-form>
    </a-modal>
</template>

<script setup>
import {
    Modal as AModal,
    Form as AForm,
    FormItem as AFormItem,
    Input as AInput,
    InputNumber as AInputNumber,
    Button as AButton,
    Upload as AUpload,
    message as AMessage,
} from "ant-design-vue";
import { PlusOutlined } from "@ant-design/icons-vue";
import { ref, watch } from "vue";
import axios from "axios";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    open: Boolean,
    etapa: Object,
    nino: {
        type: Object,
        required: true,
    },
    evaluacion: {
        type: Object,
        default: null,
    },
});
const emit = defineEmits(["cancel", "submit"]);

const form = ref({
    peso: null,
    talla: null,
    comentario_madre: "",
});
const loading = ref(false);

// Upload
const fileList = ref([]);
const previewVisible = ref(false);
const previewImage = ref("");
const fotoDescripcion = ref("");

function getBase64(file, callback) {
    const reader = new FileReader();
    reader.addEventListener("load", () => callback(reader.result));
    reader.readAsDataURL(file);
}

function beforeUpload(file) {
    const isImage = file.type.startsWith("image/");
    if (!isImage) {
        AMessage.error("Solo se permiten imágenes.");
        return false;
    }
    const isLt2M = file.size / 1024 / 1024 < 2;
    if (!isLt2M) {
        AMessage.error("La imagen debe ser menor a 2MB.");
        return false;
    }
    getBase64(file, (url) => {
        file.thumbUrl = url;
        fileList.value = [file];
    });
    return false; // No subir automáticamente
}

function handlePreview(file) {
    if (file.url) {
        previewImage.value = file.url;
    } else if (file.thumbUrl) {
        previewImage.value = file.thumbUrl;
    } else {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.value = e.target.result;
        };
        reader.readAsDataURL(file.originFileObj || file);
    }
    previewVisible.value = true;
}

function handleRemove() {
    fileList.value = [];
}

watch(
    () => props.open,
    (nuevoValor) => {
        if (nuevoValor) {
            if (props.evaluacion) {
                form.value = {
                    peso: props.evaluacion.peso ?? null,
                    talla: props.evaluacion.talla ?? null,
                    comentario_madre: props.evaluacion.comentario_madre ?? "",
                };
                // Mostrar foto y descripción si existen
                if (props.evaluacion.foto && props.evaluacion.foto.ruta_foto) {
                    fileList.value = [
                        {
                            uid: "-1",
                            name: "foto.jpg",
                            status: "done",
                            url: props.evaluacion.foto.ruta_foto,
                        },
                    ];
                    fotoDescripcion.value =
                        props.evaluacion.foto.descripcion || "";
                } else {
                    fileList.value = [];
                    fotoDescripcion.value = "";
                }
            } else {
                form.value = {
                    peso: null,
                    talla: null,
                    comentario_madre: "",
                };
                fileList.value = [];
                fotoDescripcion.value = "";
            }
        }
    }
);

function handleCancel() {
    emit("cancel");
}

async function handleSubmit() {
    loading.value = true;
    try {
        const formData = new FormData();
        formData.append("etapa_desarrollo_id", props.etapa?.id);
        formData.append("peso", form.value.peso);
        formData.append("talla", form.value.talla);
        formData.append("comentario_madre", form.value.comentario_madre);
        if (fileList.value.length > 0) {
            // Asegurarse de enviar el archivo real (File)
            const fileToSend =
                fileList.value[0].originFileObj || fileList.value[0];
            formData.append("foto", fileToSend);
            formData.append("foto_descripcion", fotoDescripcion.value);
        }
        if (props.evaluacion && props.evaluacion.id) {
            // Actualizar evaluación existente
            await axios.post(
                route("evaluaciones.update", [
                    props.nino.id,
                    props.evaluacion.id,
                ]),
                formData,
                { headers: { "Content-Type": "multipart/form-data" } }
            );
            AMessage.success("¡Evaluación actualizada correctamente!");
        } else {
            // Crear nueva evaluación
            await axios.post(
                route("evaluaciones.store", props.nino.id),
                formData,
                { headers: { "Content-Type": "multipart/form-data" } }
            );
            AMessage.success("¡Evaluación guardada correctamente!");
        }
        handleCancel();
        // Recargar la ruta actual manteniendo el scroll
        router.visit(route("etapas.por-nino", props.nino.id), {
            preserveScroll: true,
        });
    } catch (error) {
        AMessage.error("Error al guardar la evaluación");
        console.error("errorr", error);
    } finally {
        loading.value = false;
    }
}
</script>

<style scoped></style>

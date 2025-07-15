import dayjs from "dayjs";
import "dayjs/locale/es";
dayjs.locale("es");

/**
 * Formatea una fecha a texto legible.
 * @param {string|Date} fecha - Fecha a formatear
 * @param {string} tipo - 'mediano' para 'D de MMM, YYYY', por defecto 'DD/MM/YYYY'
 * @returns {string}
 */
export function formatearFecha(fecha, tipo = "") {
    if (!fecha) return "";
    if (tipo === "mediano") {
        return dayjs(fecha).format("D [de] MMM, YYYY");
    }
    return dayjs(fecha).format("DD/MM/YYYY");
}

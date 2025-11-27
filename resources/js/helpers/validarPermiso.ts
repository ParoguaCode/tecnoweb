import { SharedData } from "@/types";
import { usePage } from "@inertiajs/vue3";

const page = usePage<SharedData>();

export const puede = (permiso: string | undefined): boolean => {
    if (!permiso) return false;
    return page.props.auth && page.props.auth.permisos.includes(permiso);
}

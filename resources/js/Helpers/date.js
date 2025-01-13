import { format } from 'date-fns'
import {fr} from 'date-fns/locale'

const formatDate = (date, formatStr='d MMMM yyyy') => {
    return format(new Date(date), formatStr, {locale:fr})
}

export {formatDate}

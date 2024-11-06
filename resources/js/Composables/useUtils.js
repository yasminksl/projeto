import { format, parseISO } from 'date-fns'

export const useDateUtils = () => {
    const formatDate = (date) => {
        const parsedDate = parseISO(date)
        return format(parsedDate, 'dd/MM/yyyy')
    }
    return { formatDate }
}

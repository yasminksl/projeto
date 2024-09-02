import { format, parseISO } from "date-fns";

export const useDateUtils = () => {
    const formatDate = (date) => new Date(date).toLocaleDateString();
    return { formatDate };
};

export const formatDate = (date) => {
    const parsedDate = parseISO(date);
    return format(parsedDate, 'dd/MM/yyyy');
};

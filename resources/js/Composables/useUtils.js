export const useDateUtils = () => {
    const formatDate = (date) => new Date(date).toLocaleDateString();
    return { formatDate };
};

export const useStatusUtils = () => {
    const statusClasses = (status) => ({
        'flex-none rounded-full p-1': true,
        'bg-red-500/20': status === 'Entrega Não Agendada',
        'bg-yellow-500/20': status === 'Entrega Agendada',
        'bg-green-500/20': status === 'Concluído'
    });

    const statusIndicatorClasses = (status) => ({
        'h-1.5 w-1.5 rounded-full': true,
        'bg-red-500': status === 'Entrega Não Agendada',
        'bg-yellow-500': status === 'Entrega Agendada',
        'bg-green-500': status === 'Concluído'
    });

    return { statusClasses, statusIndicatorClasses };
};

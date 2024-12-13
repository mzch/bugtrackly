const sortingClass = (fieldName, params) => {
    return {
        'sorting' : true,
        'sorting_asc' : params.field === fieldName && params.direction === 'asc',
        'sorting_desc' : params.field === fieldName && params.direction === 'desc',
    }
}

export {sortingClass}

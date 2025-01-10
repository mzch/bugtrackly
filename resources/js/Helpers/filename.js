const getFileName = (path) => {
    return path.split('/').pop(); // Divise par "/" et prend le dernier élément
};

export {getFileName}

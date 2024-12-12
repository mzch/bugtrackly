const formatNumber = (value, decimalPlaces=0,locale='fr-FR') => {
    const number = Number(value);
    if(isNaN(number)){
        return value;
    }
    const config = {
        minimumFractionDigits: decimalPlaces,
        maximumFractionDigits: decimalPlaces
    }
    return number.toLocaleString(locale, config);
}

export {formatNumber}

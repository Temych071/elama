// noinspection JSIncompatibleTypesComparison

import moment from "moment";
import {useI18n} from "vue-i18n";

export const dateRangeFormat = (dateRange, short = false, useAlias = false, options = {}) => {
    const momentDateFormat = (date, short = false) =>
        moment(date).format(`D ${short ? 'MMM' : 'MMMM'} ${(options?.withoutYear ?? false) ? '' : 'YYYY'}`);

    let obj = makeDateRangeMomentObject(dateRange);

    if (useAlias && obj.alias !== null) {
        return useI18n().t(`period.picker.${obj.alias}`);
    }

    if (getDateRangeMomentDaysNum(obj) < 1) {
        return momentDateFormat(obj.from, short);
    }

    let from = momentDateFormat(obj.from, short).split(' ');
    let to = momentDateFormat(obj.to, short).split(' ');

    let other = [];
    for (let i = to.length - 1; i >= 0; i--) {
        if (to[i] !== from[i]) {
            break;
        }

        other.push(to[i]);
        to.pop();
        from.pop();
    }

    return `${from.join(' ')} - ${to.join(' ')} ${other.reverse().join(' ')}`;
};

const WEEK_DAYS = [
    'Пн',
    'Вт',
    'Ср',
    'Чт',
    'Пт',
    'Сб',
    'Вс',
];

export const dateRangeFormatWithWeekDay = (dateRange, short = false, options = {}) => {
    let format = dateRangeFormat(dateRange, short, false, options).split(' - ');

    let obj = makeDateRangeMomentObject(dateRange);

    let dayFrom = WEEK_DAYS[obj.from.weekday()];
    let dayTo = WEEK_DAYS[obj.to.weekday()];

    if (dayFrom === dayTo || format.length === 1) {
        return `${format.join(' - ')} (${dayFrom})`;
    }

    format[0] += ` (${dayFrom})`;
    format[1] += ` (${dayTo})`;

    return format.join(' - ');
}

export const parseDateRange = (dateRange) => {
    if (typeof dateRange !== 'string') {
        return null;
    }

    dateRange = dateRange.trim();
    if (dateRange.length < 1) {
        return null;
    }

    let fromAlias = resolveDateRangeAlias(dateRange);
    if (fromAlias != null) {
        return fromAlias;
    }

    if (isNaN(Date.parse(dateRange))) {
        // Если содержит двоеточие и не парсится как дата, значит уже период (но это не точно :) )
        if (dateRange.includes(':')) {
            return dateRange.split(':');
        }
        return null;
    }

    return {
        from: moment(dateRange),
        to: moment(dateRange),
    };
};

export const resolveDateRangeAlias = (dateRange) => {
    const f = (from, to = from) => {
        return {from, to, alias: dateRange};
    };

    switch (dateRange) {
        case 'today':
            return f(moment());
        case 'yesterday':
            return f(moment().subtract(1, 'days'));
        case 'week':
            return f(
                moment().startOf('week'),
                moment().endOf('week')
            );
        case 'last-week':
            return f(
                moment().subtract(1, 'weeks').startOf('week'),
                moment().subtract(1, 'weeks').endOf('week'),
            );
        case 'month':
            return f(
                moment().startOf('month'),
                moment().endOf('month'),
            );
        case 'last-month':
            return f(
                moment().subtract(1, 'months').startOf('month'),
                moment().subtract(1, 'months').endOf('month'),
            );
        case 'year':
            return f(
                moment().startOf('year'),
                moment().endOf('year'),
            );
        case 'last-year':
            return f(
                moment().subtract(1, 'years').startOf('year'),
                moment().subtract(1, 'years').endOf('year'),
            );
        case '7days':
            return f(
                moment().subtract(7, 'days'),
                moment().subtract(1, 'days'),
            );
        case '30days':
            return f(
                moment().subtract(30, 'days'),
                moment().subtract(1, 'days'),
            );
        case '90days':
            return f(
                moment().subtract(90, 'days'),
                moment().subtract(1, 'days'),
            )
        case '180days':
            return f(
                moment().subtract(180, 'days'),
                moment().subtract(1, 'days'),
            )
        case '365days':
            return f(
                moment().subtract(365, 'days'),
                moment().subtract(1, 'days'),
            )
    }

    return null;
};

export const dateRangeToStr = (dateRange) => {
    if (
        typeof dateRange === 'string'
        && resolveDateRangeAlias(dateRange) != null
    ) {
        // Чтобы оставлять алиасы алиасами
        return dateRange;
    }

    let obj = makeDateRangeMomentObject(dateRange);

    return `${obj.from.format('YYYY-MM-DD')}:${obj.to.format('YYYY-MM-DD')}`;
};

export const makeDateRangeMomentObject = (dateRange) => {
    if (typeof dateRange === 'string') {
        dateRange = parseDateRange(dateRange);
    }

    const f = (from, to = from, alias = null) => {
        return {
            from: moment(from),
            to: moment(to),
            alias,
        };
    };

    if (dateRange instanceof Date) {
        return f(dateRange);
    }

    if (dateRange instanceof Array) {
        return f(dateRange[0], dateRange[1] ?? dateRange[0]);
    }

    if (dateRange instanceof Object && Object.getPrototypeOf(dateRange) === Object.prototype) {
        return f(dateRange.from, dateRange.to, dateRange.alias ?? null);
    }

    return null;
};

export const getDateRangeMomentDaysNum = (dateRange) => {
    let obj = makeDateRangeMomentObject(dateRange);
    return Math.abs(obj?.from?.diff(obj.to, 'days', true) ?? 0);
};

export const getPreviousDateRange = (dateRange) => {
    let obj = makeDateRangeMomentObject(dateRange)
    let daysNum = getDateRangeMomentDaysNum(obj);

    obj.from.subtract(1 + daysNum, 'days');
    obj.to.subtract(1 + daysNum, 'days');

    return obj;
};

export default {
    resolveDateRangeAlias,
    dateRangeToStr,
    makeDateRangeMomentObject,
    getDateRangeMomentDaysNum,
    getPreviousDateRange,
};

import EnLang from './En'
import ArLang from './Ar'
import RuLang from './Ru'
export default function Lang() {
    if (document.documentElement.lang == 'en') {
        return EnLang
    }
    if (document.documentElement.lang == 'ru') {
        return RuLang
    }
    else {
        return EnLang
    }
}

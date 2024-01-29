const host = window.location.host;
const protocol = "https:";
const apiDomain = process.env.REACT_APP_API_DOMAIN || "guardians.one";
const apiDomainProd = process.env.REACT_APP_API_DOMAIN_PROD || "cq9web.com";
const apiDomainPrefix = process.env.REACT_APP_API_DOMAIN_PREFIX || "";
const domains = {
    dev: `${apiDomainPrefix}dev-gapi.${apiDomain}/api`,
    qa: `${apiDomainPrefix}qa-gapi.${apiDomain}/api`,
    int: `${apiDomainPrefix}int-gapi.${apiDomain}/api`,
    clinetInt: `gapi.cqgame.games/api`,
    prod: `gapi.${apiDomainProd}/api`,
};
export const fetchGapiDomain = ({
    getDev = "dev-",
    getQa = "qa-",
    getInt = "int-",
    isclinetInt = false,
}) => {
    const local = !!~host.indexOf(":");
    const isDev = !!~host.indexOf(getDev);
    const isQa = !!~host.indexOf(getQa);
    const isInt = !!~host.indexOf(getInt);
    if (local || isDev) {
        return `${protocol}//${domains.dev}`;
    } else if (isQa) {
        return `${protocol}//${domains.qa}`;
        // } else if (is168) {
        //   return `${protocol}//${host}/api`;
    } else if (isInt) {
        return isclinetInt ?
            `${protocol}//${domains.clinetInt}` :
            `${protocol}//${domains.int}`;
    } else return `${protocol}//${domains.prod}`;
};
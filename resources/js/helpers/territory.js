export function getLocalTerritory () {
    const territoryStr = localStorage.getItem("territory");

    if(!territoryStr) {
        return null;
    }

    return JSON.parse(territoryStr);
}
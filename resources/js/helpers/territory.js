export function getLocalTerritory () {
    const territory = localStorage.getItem("territory");

    if(!territory) {
        return null;
    }

    return JSON.parse(territory);
}
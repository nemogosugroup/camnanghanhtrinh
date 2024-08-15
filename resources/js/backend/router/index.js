/* Router Modules */
import missionsRouter from "./modules/missions";
import medalsRouter from "./modules/medals";
import levelsRouter from "./modules/levels";
import equipmentsRouter from "./modules/equipments";
import coursesRouter from "./modules/courses";
//import vuLanRouter from "./modules/vulan";
import vuLanDetailRouter from "./modules/detailVuLan";
import templateVulanRouter from "./modules/templatevulan";
export const adminRoute = [
    levelsRouter,
    medalsRouter,
    missionsRouter,
    equipmentsRouter,
    coursesRouter,
    //vuLanRouter,
    //vuLanDetailRouter,
    templateVulanRouter,
];

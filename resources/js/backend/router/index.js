
/* Router Modules */
import missionsRouter from "./modules/missions";
import medalsRouter from "./modules/medals";
import levelsRouter from "./modules/levels";
import equipmentsRouter from "./modules/equipments";
import coursesRouter from "./modules/courses";
export const adminRoute = [
    levelsRouter,
    medalsRouter,
    missionsRouter,
    equipmentsRouter,
    coursesRouter, 
]
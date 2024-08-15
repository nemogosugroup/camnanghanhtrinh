import UserRepository from "@/api/user/index";
import RoleRepository from "@/api/role";
import ProjectRepository from "@/api/project";
import MarketRepository from "@/api/market";
import QuestRepository from "@/api/quest";
import userEquipmentRepository from "@/api/equipment/index";
import vulanRepository from "@/api/vulan/index";
const repositories = {
    user: UserRepository,
    role: RoleRepository,
    project: ProjectRepository,
    market: MarketRepository,
    quest: QuestRepository,
    userEquipment: userEquipmentRepository,
    vulan: vulanRepository,
};
const RepositoryFactory = {
    get: (name) => repositories[name],
};
export default RepositoryFactory;

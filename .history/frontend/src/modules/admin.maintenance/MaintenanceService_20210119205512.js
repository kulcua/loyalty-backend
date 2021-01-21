export default class MaintenanceService {
  constructor(Restangular) {
    this.Restangular = Restangular;
  }

  getLevels(params) {
    return this.Restangular.all("level").getList(params);
  }
}

MaintenanceService.$inject = ["Restangular"];

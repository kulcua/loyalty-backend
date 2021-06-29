export default class MaintenanceService {
  constructor(Restangular, EditableMap) {
    this.Restangular = Restangular;
    this.EditableMap = EditableMap;
  }

  getMaintenances(params) {
    return this.Restangular.all("maintenance").getList(params);
  }

  getMaintenance(maintenanceId) {
    return this.Restangular.one("maintenance", maintenanceId).get();
  }

  postMaintenance(newMaintenance) {
    return this.Restangular.one("maintenance").customPOST({
      maintenance: newMaintenance,
    });
  }

  getMaintenanceCustomers(params, maintenanceId) {
    if (!params) {
      params = {};
    }
    return this.Restangular.one("level", maintenanceId)
      .all("customers")
      .getList();
  }
  getFile(maintenanceId) {
    return this.Restangular.one("csv").one("level", maintenanceId).get();
  }

  putMaintenance(maintenanceId, editedMaintenance) {
    let self = this;

    return self.Restangular.one("maintenance", maintenanceId).customPUT({
      maintenance: self.Restangular.stripRestangular(
        self.EditableMap.maintenance(editedMaintenance)
      ),
    });
  }

  postActivateMaintenance(state, maintenanceId) {
    let self = this;

    return this.Restangular.one("maintenance")
      .one(maintenanceId)
      .one(state?"active":"inactive")
      .customPOST();
  }
}

MaintenanceService.$inject = ["Restangular", "EditableMap"];

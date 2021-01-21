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
    console.log(newMaintenance);
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

  putMaintenance(editedMaintenance) {
    let self = this;

    return editedMaintenance.customPUT({
      maintenance: self.EditableMap.maintenance(editedMaintenance),
    });
  }

  postActivateMaintenance(state, maintenanceId) {
    let self = this;

    return this.Restangular.one("maintenance")
      .one(maintenanceId)
      .one("activate")
      .customPOST({ active: state });
  }
}

MaintenanceService.$inject = ["Restangular", "EditableMap"];

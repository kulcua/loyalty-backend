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
      .one(state ? "active" : "inactive")
      .customPOST();
  }

  /**
   * Calls for post image to maintenance
   *
   * @method postMaintenanceImage
   * @param {Integer} maintenanceId
   * @param {Object} data
   * @returns {Promise}
   */
  postMaintenanceImage(maintenanceId, data) {
    let fd = new FormData();

    fd.append("photo[file]", data);

    return this.Restangular.one("maintenance", maintenanceId)
      .one("photo")
      .withHttpConfig({ transformRequest: angular.identity })
      .customPOST(fd, "", undefined, { "Content-Type": undefined });
  }

  /**
   * Calls for maintenance image
   *
   * @method getMaintenanceImage
   * @param {Integer} maintenanceId
   * @returns {Promise}
   */
  getMaintenanceImage(maintenanceId) {
    return this.Restangular.one("level", maintenanceId).one("photo").get();
  }

  /**
   * Calls to remove maintenance photo
   *
   * @method deleteMaintenanceImage
   * @param {Integer} maintenanceId
   * @returns {Promise}
   */
  deleteMaintenanceImage(maintenanceId) {
    return this.Restangular.one("maintenance", maintenanceId)
      .one("photo")
      .remove();
  }
}

MaintenanceService.$inject = ["Restangular", "EditableMap"];

export default class WarrantyService {
  constructor(Restangular, EditableMap) {
    this.Restangular = Restangular;
    this.EditableMap = EditableMap;
  }

  getWarrantys(params) {
    return this.Restangular.all("warranty").getList(params);
  }

  getWarranty(warrantyId) {
    return this.Restangular.one("warranty", warrantyId).get();
  }

  postWarranty(newWarranty) {
    return this.Restangular.one("warranty").customPOST({
      warranty: newWarranty,
    });
  }

  getWarrantyCustomers(params, warrantyId) {
    if (!params) {
      params = {};
    }
    return this.Restangular.one("level", warrantyId)
      .all("customers")
      .getList();
  }
  getFile(warrantyId) {
    return this.Restangular.one("csv").one("level", warrantyId).get();
  }

  putWarranty(warrantyId, editedWarranty) {
    let self = this;

    return self.Restangular.one("warranty", warrantyId).customPUT({
      warranty: self.Restangular.stripRestangular(
        self.EditableMap.warranty(editedWarranty)
      ),
    });
  }

  postActivateWarranty(state, warrantyId) {
    let self = this;

    return this.Restangular.one("warranty")
      .one(warrantyId)
      .one(state?"active":"inactive")
      .customPOST();
  }
}

WarrantyService.$inject = ["Restangular", "EditableMap"];

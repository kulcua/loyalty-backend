export default class MaintenanceController {
  constructor(
    $scope,
    $state,
    $timeout,
    AuthService,
    MaintenanceService,
    Flash,
    NgTableParams,
    $q,
    ParamsMap,
    $stateParams,
    EditableMap,
    Validation,
    $filter,
    DataService
  ) {
    if (!AuthService.isGranted("ROLE_ADMIN")) {
      AuthService.logout();
    }
    this.$scope = $scope;
    this.MaintenanceService = MaintenanceService;
    this.$state = $state;
    this.AuthService = AuthService;
    this.Flash = Flash;
    this.maintenanceId = $stateParams.maintenanceId || null;
    this.maintenanceName = $stateParams.maintenanceName || null;
    this.NgTableParams = NgTableParams;
    this.ParamsMap = ParamsMap;
    this.EditableMap = EditableMap;
    this.$q = $q;
    this.Validation = Validation;
    this.$filter = $filter;
    this.$timeout = $timeout;
    this.config = DataService.getConfig();
    this.DataService = DataService;
    this.$scope.fileValidate = {};
    // If 'required: true' it will only
    // be required on default language
    this.$scope.translatableFields = [
      {
        key: "name",
        label: "maintenance.name",
        prompt: "maintenance.name_prompt",
        required: true,
      },
      {
        key: "description",
        label: "maintenance.description",
        prompt: "maintenance.description_prompt",
        required: false,
      },
    ];
    this.$scope.availableFrontendTranslations = this.DataService.getAvailableFrontendTranslations();
    this.active = [
      {
        name: this.$filter("translate")("global.active"),
        type: 1,
      },
      {
        name: this.$filter("translate")("global.inactive"),
        type: 0,
      },
    ];
    this.activeConfig = {
      valueField: "type",
      labelField: "name",
      create: false,
      sortField: "name",
      maxItems: 1,
    };
    this.loaderStates = {
      maintenanceList: true,
      maintenanceDetails: true,
      userList: true,
      coverLoader: true,
    };
  }

  getData() {
    let self = this;

    self.tableParams = new self.NgTableParams(
      {
        count: self.config.perPage,
      },
      {
        getData: function (params) {
          let dfd = self.$q.defer();

          self.loaderStates.maintenanceList = true;
          self.MaintenanceService.getMaintenances(
            self.ParamsMap.params(params.url())
          ).then(
            (res) => {
              self.$scope.maintenances = res;
              print(res);
              params.total(res.total);
              self.loaderStates.maintenanceList = false;
              self.loaderStates.coverLoader = false;
              dfd.resolve(res);
            },
            () => {
              let message = self.$filter("translate")(
                "xhr.get_maintenances_list.error"
              );
              self.Flash.create("danger", message);
              self.loaderStates.maintenanceList = false;
              self.loaderStates.coverLoader = false;
              dfd.reject();
            }
          );

          return dfd.promise;
        },
      }
    );
  }

  getMaintenanceCustomersData() {
    let self = this;

    if (self.maintenanceId) {
      self.tableCustomerParams = new self.NgTableParams(
        {},
        {
          getData: function (params) {
            let dfd = self.$q.defer();
            self.loaderStates.userList = true;

            self.MaintenanceService.getMaintenanceCustomers(
              self.ParamsMap.params(params.url()),
              self.maintenanceId
            ).then(
              function (res) {
                self.$scope.customers = res;
                params.total(res.total);
                self.loaderStates.userList = false;
                self.loaderStates.coverLoader = false;
                dfd.resolve(res);
              },
              function () {
                let message = self.$filter("translate")(
                  "xhr.get_maintenance_customers.error"
                );
                self.Flash.create("danger", message);
                self.loaderStates.userList = false;
                self.loaderStates.coverLoader = false;
                dfd.reject();
              }
            );

            return dfd.promise;
          },
        }
      );
    } else {
      self.$state.go("admin.maintenances-list");
      let message = self.$filter("translate")(
        "xhr.get_maintenance_customers.no_id"
      );
      self.Flash.create("warning", message);
      self.loaderStates.userList = false;
      self.loaderStates.coverLoader = false;
    }
  }

  getMaintenanceData() {
    let self = this;

    if (self.maintenanceId) {
      self.MaintenanceService.getMaintenance(self.maintenanceId).then(
        (res) => {
          self.$scope.maintenance = res;
          self.$scope.editableFields = self.EditableMap.humanizeMaintenance(
            res
          );
          self.loaderStates.coverLoader = false;
        },
        () => {
          let message = self.$filter("translate")(
            "xhr.get_maintenances_list.error"
          );
          self.Flash.create("danger", message);
          self.loaderStates.coverLoader = false;
        }
      );

      self.MaintenanceService.getMaintenanceImage(self.maintenanceId)
        .then((res) => {
          self.$scope.maintenanceImagePath = true;
        })
        .catch((err) => {
          self.$scope.maintenanceImagePath = false;
        });
    } else {
      self.$state.go("admin.maintenances-list");
      let message = self.$filter("translate")(
        "xhr.get_single_maintenance.no_id"
      );
      self.Flash.create("warning", message);
      self.loaderStates.coverLoader = false;
    }
  }

  getMaintenanceCsvData(maintenanceId, maintenanceName) {
    let self = this;
    if (maintenanceId) {
      self.MaintenanceService.getFile(maintenanceId).then(function (res) {
        let date = new Date();
        let filename =
          maintenanceName.replace(" ", "-") +
          "-" +
          date.toISOString().substring(0, 10) +
          ".csv";
        let blob = new Blob([res], { type: "text/csv" });
        if (window.navigator && window.navigator.msSaveOrOpenBlob) {
          window.navigator.msSaveOrOpenBlob(blob);
          return;
        }

        const data = window.URL.createObjectURL(blob);

        let link = document.createElement("a");
        link.href = data;
        link.download = filename;
        self.$timeout(function () {
          link.dispatchEvent(new MouseEvent("click"));
        }, 2000);
      });
    }
  }

  addMaintenance(newMaintenance) {
    let self = this;

    self.MaintenanceService.postMaintenance(newMaintenance).then(
      (res) => {
        if (self.$scope.maintenanceImage) {
          self.$scope.fileValidate = {};

          self.MaintenanceService.postMaintenanceImage(
            res.id,
            self.$scope.maintenanceImage
          )
            .then((res2) => {
              self.$state.go("admin.maintenances-list", {
                maintenanceId: self.maintenanceId,
              });
              let message = self.$filter("translate")(
                "xhr.post_single_maintenance.success"
              );
              self.Flash.create("success", message);
            })
            .catch((err) => {
              self.$scope.fileValidate = self.Validation.mapSymfonyValidation(
                err.data
              );
              self.MaintenanceService.storedFileError =
                self.$scope.fileValidate;

              let message = self.$filter("translate")(
                "xhr.post_single_maintenance.warning"
              );
              self.Flash.create("warning", message);

              self.$state.go("admin.edit-maintenance", {
                maintenanceId: res.id,
              });
            });
        } else {
          self.$state.go("admin.maintenances-list");
          let message = self.$filter("translate")(
            "xhr.post_single_maintenance.success"
          );
          self.Flash.create("success", message);
        }
      },
      (res) => {
        self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
        let message = self.$filter("translate")(
          "xhr.post_single_maintenance.error"
        );
        self.Flash.create("danger", message);
      }
    );
  }

  editMaintenance(editedMaintenance) {
    let self = this;

    self.MaintenanceService.putMaintenance(editedMaintenance).then(
      (res) => {
        if (self.$scope.maintenanceImage) {
          self.$scope.fileValidate = {};

          self.MaintenanceService.postMaintenanceImage(
            self.maintenanceId,
            self.$scope.maintenanceImage
          )
            .then((res2) => {
              self.$state.go("admin.maintenances-list", {
                maintenanceId: self.maintenanceId,
              });
              let message = self.$filter("translate")(
                "xhr.put_single_maintenance.success"
              );
              self.Flash.create("success", message);
            })
            .catch((err) => {
              self.$scope.fileValidate = self.Validation.mapSymfonyValidation(
                err.data
              );
              self.MaintenanceService.storedFileError =
                self.$scope.fileValidate;

              let message = self.$filter("translate")(
                "xhr.put_single_maintenance.warning"
              );
              self.Flash.create("warning", message);

              self.$state.go("admin.edit-maintenance", {
                maintenanceId: self.maintenanceId,
              });
            });
        } else {
          let message = self.$filter("translate")(
            "xhr.put_single_maintenance.success"
          );
          self.Flash.create("success", message);
          self.$state.go("admin.maintenances-list");
        }
      },
      (res) => {
        self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
        let message = self.$filter("translate")(
          "xhr.put_single_maintenance.error"
        );
        self.Flash.create("danger", message);
      }
    );
  }

  addSpecialReward(edit) {
    let self = this;
    let maintenance;

    if (!edit) {
      maintenance = self.$scope.newMaintenance;
    } else {
      maintenance = self.$scope.editableFields;
    }

    maintenance.specialRewards.push({
      active: 0,
      startAt: "",
      endAt: "",
    });
  }

  removeSpecialReward(index, edit) {
    let self = this;
    let maintenance;

    if (!edit) {
      maintenance = self.$scope.newMaintenance;
    } else {
      maintenance = self.$scope.editableFields;
    }

    maintenance.specialRewards = _.difference(maintenance.specialRewards, [
      maintenance.specialRewards[index],
    ]);
  }

  setMaintenanceState(state, maintenanceId) {
    let self = this;

    self.MaintenanceService.postActivateMaintenance(state, maintenanceId).then(
      () => {
        let message = self.$filter("translate")(
          "xhr.post_activate_maintenance.success"
        );
        self.Flash.create("success", message);
        self.tableParams.reload();
      },
      () => {
        let message = self.$filter("translate")(
          "xhr.post_activate_maintenance.error"
        );
        self.Flash.create("danger", message);
      }
    );
  }

  /**
   * Deletes photo
   *
   * @method deletePhoto
   */
  deletePhoto() {
    let self = this;

    this.MaintenanceService.deleteMaintenanceImage(this.maintenanceId)
      .then((res) => {
        self.$scope.maintenanceImagePath = false;
        let message = self.$filter("translate")(
          "xhr.delete_maintenance_image.success"
        );
        self.Flash.create("success", message);
      })
      .catch((err) => {
        self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
        let message = self.$filter("translate")(
          "xhr.delete_maintenance_image.error"
        );
        self.Flash.create("danger", message);
      });
  }

  /**
   * Generating photo route
   *
   * @method generatePhotoRoute
   * @returns {string}
   */
  generatePhotoRoute() {
    return this.config.apiUrl + "/maintenance/" + this.maintenanceId + "/photo";
  }
}

MaintenanceController.$inject = [
  "$scope",
  "$state",
  "$timeout",
  "AuthService",
  "MaintenanceService",
  "Flash",
  "NgTableParams",
  "$q",
  "ParamsMap",
  "$stateParams",
  "EditableMap",
  "Validation",
  "$filter",
  "DataService",
];

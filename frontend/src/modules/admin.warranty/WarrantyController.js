export default class WarrantyController {
  constructor(
    $scope,
    $state,
    $timeout,
    AuthService,
    WarrantyService,
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
    this.WarrantyService = WarrantyService;
    this.$state = $state;
    this.AuthService = AuthService;
    this.Flash = Flash;
    this.warrantyId = $stateParams.warrantyId || null;
    this.warrantyName = $stateParams.warrantyName || null;
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
        label: "Tên khách hàng",
        required: true,
      },
      {
        key: "phone",
        label: "Số điện thoại",
        required: true,
      },
      {
        key: "email",
        label: "Email",
        required: false,
      },
    ];
    this.$scope.availableFrontendTranslations = this.DataService.getAvailableFrontendTranslations();
    this.active = [
      {
        name: "Có hiêụ lực",
        type: true,
      },
      {
        name: "Hết hiệu lực",
        type: false,
      },
    ];
    this.activeConfig = {
      valueField: "type",
      labelField: "name",
      create: false,
      sortField: "name",
      maxItems: 1,
    };
    this.activePayment = [
      {
        name: "Đã thanh toán",
        type: true,
      },
      {
        name: "Chưa thanh toán",
        type: false,
      },
    ];
    this.activePaymentConfig = {
      valueField: "type",
      labelField: "name",
      create: false,
      sortField: "name",
      maxItems: 1,
    };
    this.loaderStates = {
      warrantyList: true,
      warrantyDetails: true,
      userList: true,
      coverLoader: true,
    };
  }

  Ctrl($scope) {
    $scope.date = new Date();
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
          self.loaderStates.warrantyList = true;
          self.WarrantyService.getWarrantys(
            self.ParamsMap.params(params.url())
          ).then(
            (res) => {
              self.$scope.warrantys = res;
              params.total(res.total);
              self.loaderStates.warrantyList = false;
              self.loaderStates.coverLoader = false;
              dfd.resolve(res);
            },
            () => {
              let message = self.$filter("translate")(
                "xhr.get_warrantys_list.error"
              );
              self.Flash.create("danger", message);
              self.loaderStates.warrantyList = false;
              self.loaderStates.coverLoader = false;
              dfd.reject();
            }
          );

          return dfd.promise;
        },
      }
    );
  }

  getWarrantyCustomersData() {
    let self = this;

    if (self.warrantyId) {
      self.tableCustomerParams = new self.NgTableParams(
        {},
        {
          getData: function (params) {
            let dfd = self.$q.defer();
            self.loaderStates.userList = true;

            self.WarrantyService.getWarrantyCustomers(
              self.ParamsMap.params(params.url()),
              self.warrantyId
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
                  "xhr.get_warranty_customers.error"
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
      self.$state.go("admin.warrantys-list");
      let message = self.$filter("translate")(
        "xhr.get_warranty_customers.no_id"
      );
      self.Flash.create("warning", message);
      self.loaderStates.userList = false;
      self.loaderStates.coverLoader = false;
    }
  }

  getWarrantyData() {
    let self = this;

    if (self.warrantyId) {
      self.WarrantyService.getWarranty(self.warrantyId).then(
        (res) => {
          self.$scope.warranty = res;
          self.$scope.editableFields = self.EditableMap.humanizeWarranty(
            res
          );
          self.loaderStates.coverLoader = false;
        },
        () => {
          let message = self.$filter("translate")(
            "xhr.get_warrantys_list.error"
          );
          self.Flash.create("danger", message);
          self.loaderStates.coverLoader = false;
        }
      );

      self.WarrantyService.getWarrantyImage(self.warrantyId)
        .then((res) => {
          self.$scope.warrantyImagePath = true;
        })
        .catch((err) => {
          self.$scope.warrantyImagePath = false;
        });
    } else {
      self.$state.go("admin.warrantys-list");
      let message = self.$filter("translate")(
        "xhr.get_single_warranty.no_id"
      );
      self.Flash.create("warning", message);
      self.loaderStates.coverLoader = false;
    }
  }

  addWarranty(newWarranty) {
    let self = this;

    self.WarrantyService.postWarranty(newWarranty).then(
      (res) => {
        if (self.$scope.warrantyImage) {
          self.$scope.fileValidate = {};

          self.WarrantyService.postWarrantyImage(
            res.id,
            self.$scope.warrantyImage
          )
            .then((res2) => {
              self.$state.go("admin.warrantys-list", {
                warrantyId: self.warrantyId,
              });
              let message = self.$filter("translate")(
                "xhr.post_single_warranty.success"
              );
              self.Flash.create("success", message);
            })
            .catch((err) => {
              self.$scope.fileValidate = self.Validation.mapSymfonyValidation(
                err.data
              );
              self.WarrantyService.storedFileError =
                self.$scope.fileValidate;

              let message = self.$filter("translate")(
                "xhr.post_single_warranty.warning"
              );
              self.Flash.create("warning", message);

              self.$state.go("admin.edit-warranty", {
                warrantyId: res.id,
              });
            });
        } else {
          self.$state.go("admin.warrantys-list");
          let message = self.$filter("translate")(
            "xhr.post_single_warranty.success"
          );
          self.Flash.create("success", message);
        }
      },
      (res) => {
        self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
        let message = self.$filter("translate")(
          "xhr.post_single_warranty.error"
        );
        self.Flash.create("danger", message);
      }
    );
  }

  editWarranty(editedWarranty) {
    let self = this;
    let validateFields = angular.copy(self.$scope.frontValidate);
    let frontValidation = self.Validation.frontValidation(
      editedWarranty,
      validateFields
    );
    if (_.isEmpty(frontValidation)) {
      self.WarrantyService.putWarranty(
        self.warrantyId,
        editedWarranty
      ).then(
        (res) => {
          let message = self.$filter("translate")(
            "xhr.put_warranty.success"
          );
          self.Flash.create("success", message);
          self.$state.go("admin.warrantys-list");
        },
        (res) => {
          self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
          let message = self.$filter("translate")("xhr.put_warranty.error");
          self.Flash.create("danger", message);
        }
      );
    } else {
      self.$scope.validate = frontValidation;
      let message = self.$filter("translate")("xhr.put_warranty.error");
      self.Flash.create("danger", message);
    }
  }
  addSpecialReward(edit) {
    let self = this;
    let warranty;

    if (!edit) {
      warranty = self.$scope.newWarranty;
    } else {
      warranty = self.$scope.editableFields;
    }

    warranty.specialRewards.push({
      active: 0,
      startAt: "",
      endAt: "",
    });
  }

  removeSpecialReward(index, edit) {
    let self = this;
    let warranty;

    if (!edit) {
      warranty = self.$scope.newWarranty;
    } else {
      warranty = self.$scope.editableFields;
    }

    warranty.specialRewards = _.difference(warranty.specialRewards, [
      warranty.specialRewards[index],
    ]);
  }

  setWarrantyState(state, warrantyId) {
    let self = this;

    self.WarrantyService.postActivateWarranty(state, warrantyId).then(
      () => {
        let message = self.$filter("translate")(
          "xhr.post_activate_warranty.success"
        );
        self.Flash.create("success", message);
        self.tableParams.reload();
      },
      () => {
        let message = self.$filter("translate")(
          "xhr.post_activate_warranty.error"
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

    this.WarrantyService.deleteWarrantyImage(this.warrantyId)
      .then((res) => {
        self.$scope.warrantyImagePath = false;
        let message = self.$filter("translate")(
          "xhr.delete_warranty_image.success"
        );
        self.Flash.create("success", message);
      })
      .catch((err) => {
        self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
        let message = self.$filter("translate")(
          "xhr.delete_warranty_image.error"
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
    return this.config.apiUrl + "/warranty/" + this.warrantyId + "/photo";
  }
}

WarrantyController.$inject = [
  "$scope",
  "$state",
  "$timeout",
  "AuthService",
  "WarrantyService",
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

export default class SuggestionBoxController {
  constructor(
    $scope,
    $state,
    $timeout,
    AuthService,
    SuggestionBoxService,
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
    this.SuggestionBoxService = SuggestionBoxService;
    this.$state = $state;
    this.AuthService = AuthService;
    this.Flash = Flash;
    this.levelId = $stateParams.levelId || null;
    this.levelName = $stateParams.levelName || null;
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
    // this.$scope.translatableFields = [
    //   {
    //     key: "name",
    //     label: "level.name",
    //     prompt: "level.name_prompt",
    //     required: true,
    //   },
    //   {
    //     key: "description",
    //     label: "level.description",
    //     prompt: "level.description_prompt",
    //     required: false,
    //   },
    // ];
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
      suggestionBoxList: false,
      suggestionBoxDetails: true,
      userList: true,
      coverLoader: false,
    };
    // this.testSuggestionBox = [
    //   {
    //     id : "1",
    //     senderPhone: "090909009",
    //     senderName: "Kim Phuong",
    //     problemType: "Buon Ngu",
    //     description: "Lam quai khong het deadline moi ng oi",
    //     active: true,
    //     photo: "asdfsaf",
    //     timestamp : "12",
    //     showModal: false
    //   },
    //   {
    //     id:"2",
    //     senderPhone: "090909009",
    //     senderName: "Kim Phuong",
    //     problemType: "Buon Ngu",
    //     description: "Lam quai khong het deadline moi ng oi",
    //     active: true,
    //     photo: "asdfsaf",
    //     timestamp : "12",
    //     showModal: false
    //   },
    //   {
    //     id:"3",
    //     senderPhone: "090909009",
    //     senderName: "Kim Phuong",
    //     problemType: "Buon Ngu",
    //     description: "Lam quai khong het deadline moi ng oi",
    //     active: true,
    //     photo: "asdfsaf",
    //     timestamp : "12",
    //     showModal: false
    //   }
    // ]
     
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
          self.loaderStates.suggestionBoxList = true;
          self.SuggestionBoxService.getSuggestionBoxs(
            self.ParamsMap.params(params.url())
          ).then(
            (res) => {
              self.$scope.suggestionBoxs = res;
              params.total(res.total);
              self.loaderStates.suggestionBoxList = false;
              self.loaderStates.coverLoader = false;
              dfd.resolve(res);
            },
            () => {
              let suggestionBox = self.$filter("translate")(
                "xhr.get_suggestion_boxs_list.error"
              );
              self.Flash.create("danger", suggestionBox);
              self.loaderStates.suggestionBoxList = false;
              self.loaderStates.coverLoader = false;
              dfd.reject();
            }
          );

          return dfd.promise;
        },
      }
    );
  }

  // getLevelCustomersData() {
  //   let self = this;

  //   if (self.levelId) {
  //     self.tableCustomerParams = new self.NgTableParams(
  //       {},
  //       {
  //         getData: function (params) {
  //           let dfd = self.$q.defer();
  //           self.loaderStates.userList = true;

  //           self.SuggestionBoxService.getLevelCustomers(
  //             self.ParamsMap.params(params.url()),
  //             self.levelId
  //           ).then(
  //             function (res) {
  //               self.$scope.customers = res;
  //               params.total(res.total);
  //               self.loaderStates.userList = false;
  //               self.loaderStates.coverLoader = false;
  //               dfd.resolve(res);
  //             },
  //             function () {
  //               let suggestionBox = self.$filter("translate")(
  //                 "xhr.get_level_customers.error"
  //               );
  //               self.Flash.create("danger", suggestionBox);
  //               self.loaderStates.userList = false;
  //               self.loaderStates.coverLoader = false;
  //               dfd.reject();
  //             }
  //           );

  //           return dfd.promise;
  //         },
  //       }
  //     );
  //   } else {
  //     self.$state.go("admin.levels-list");
  //     let suggestionBox = self.$filter("translate")("xhr.get_level_customers.no_id");
  //     self.Flash.create("warning", suggestionBox);
  //     self.loaderStates.userList = false;
  //     self.loaderStates.coverLoader = false;
  //   }
  // }

  // getLevelData() {
  //   let self = this;

  //   if (self.levelId) {
  //     self.SuggestionBoxService.getLevel(self.levelId).then(
  //       (res) => {
  //         self.$scope.level = res;
  //         self.$scope.editableFields = self.EditableMap.humanizeLevel(res);
  //         self.loaderStates.coverLoader = false;
  //       },
  //       () => {
  //         let suggestionBox = self.$filter("translate")("xhr.get_levels_list.error");
  //         self.Flash.create("danger", suggestionBox);
  //         self.loaderStates.coverLoader = false;
  //       }
  //     );

  //     self.SuggestionBoxService.getLevelImage(self.levelId)
  //       .then((res) => {
  //         self.$scope.levelImagePath = true;
  //       })
  //       .catch((err) => {
  //         self.$scope.levelImagePath = false;
  //       });
  //   } else {
  //     self.$state.go("admin.levels-list");
  //     let suggestionBox = self.$filter("translate")("xhr.get_single_level.no_id");
  //     self.Flash.create("warning", suggestionBox);
  //     self.loaderStates.coverLoader = false;
  //   }
  // }

  // getLevelCsvData(levelId, levelName) {
  //   let self = this;
  //   if (levelId) {
  //     self.SuggestionBoxService.getFile(levelId).then(function (res) {
  //       let date = new Date();
  //       let filename =
  //         levelName.replace(" ", "-") +
  //         "-" +
  //         date.toISOString().substring(0, 10) +
  //         ".csv";
  //       let blob = new Blob([res], { type: "text/csv" });
  //       if (window.navigator && window.navigator.msSaveOrOpenBlob) {
  //         window.navigator.msSaveOrOpenBlob(blob);
  //         return;
  //       }

  //       const data = window.URL.createObjectURL(blob);

  //       let link = document.createElement("a");
  //       link.href = data;
  //       link.download = filename;
  //       self.$timeout(function () {
  //         link.dispatchEvent(new MouseEvent("click"));
  //       }, 2000);
  //     });
  //   }
  // }

  // addLevel(newLevel) {
  //   let self = this;

  //   self.SuggestionBoxService.postLevel(newLevel).then(
  //     (res) => {
  //       if (self.$scope.levelImage) {
  //         self.$scope.fileValidate = {};

  //         self.SuggestionBoxService.postLevelImage(res.id, self.$scope.levelImage)
  //           .then((res2) => {
  //             self.$state.go("admin.levels-list", { levelId: self.levelId });
  //             let suggestionBox = self.$filter("translate")(
  //               "xhr.post_single_level.success"
  //             );
  //             self.Flash.create("success", suggestionBox);
  //           })
  //           .catch((err) => {
  //             self.$scope.fileValidate = self.Validation.mapSymfonyValidation(
  //               err.data
  //             );
  //             self.SuggestionBoxService.storedFileError = self.$scope.fileValidate;

  //             let suggestionBox = self.$filter("translate")(
  //               "xhr.post_single_level.warning"
  //             );
  //             self.Flash.create("warning", suggestionBox);

  //             self.$state.go("admin.edit-level", { levelId: res.id });
  //           });
  //       } else {
  //         self.$state.go("admin.levels-list");
  //         let suggestionBox = self.$filter("translate")(
  //           "xhr.post_single_level.success"
  //         );
  //         self.Flash.create("success", suggestionBox);
  //       }
  //     },
  //     (res) => {
  //       self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
  //       let suggestionBox = self.$filter("translate")("xhr.post_single_level.error");
  //       self.Flash.create("danger", suggestionBox);
  //     }
  //   );
  // }

  // editLevel(editedLevel) {
  //   let self = this;

  //   self.SuggestionBoxService.putLevel(editedLevel).then(
  //     (res) => {
  //       if (self.$scope.levelImage) {
  //         self.$scope.fileValidate = {};

  //         self.SuggestionBoxService.postLevelImage(self.levelId, self.$scope.levelImage)
  //           .then((res2) => {
  //             self.$state.go("admin.levels-list", { levelId: self.levelId });
  //             let suggestionBox = self.$filter("translate")(
  //               "xhr.put_single_level.success"
  //             );
  //             self.Flash.create("success", suggestionBox);
  //           })
  //           .catch((err) => {
  //             self.$scope.fileValidate = self.Validation.mapSymfonyValidation(
  //               err.data
  //             );
  //             self.SuggestionBoxService.storedFileError = self.$scope.fileValidate;

  //             let suggestionBox = self.$filter("translate")(
  //               "xhr.put_single_level.warning"
  //             );
  //             self.Flash.create("warning", suggestionBox);

  //             self.$state.go("admin.edit-level", { levelId: self.levelId });
  //           });
  //       } else {
  //         let suggestionBox = self.$filter("translate")(
  //           "xhr.put_single_level.success"
  //         );
  //         self.Flash.create("success", suggestionBox);
  //         self.$state.go("admin.levels-list");
  //       }
  //     },
  //     (res) => {
  //       self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
  //       let suggestionBox = self.$filter("translate")("xhr.put_single_level.error");
  //       self.Flash.create("danger", suggestionBox);
  //     }
  //   );
  // }

  // addSpecialReward(edit) {
  //   let self = this;
  //   let level;

  //   if (!edit) {
  //     level = self.$scope.newLevel;
  //   } else {
  //     level = self.$scope.editableFields;
  //   }

  //   level.specialRewards.push({
  //     active: 0,
  //     startAt: "",
  //     endAt: "",
  //   });
  // }

  // removeSpecialReward(index, edit) {
  //   let self = this;
  //   let level;

  //   if (!edit) {
  //     level = self.$scope.newLevel;
  //   } else {
  //     level = self.$scope.editableFields;
  //   }

  //   level.specialRewards = _.difference(level.specialRewards, [
  //     level.specialRewards[index],
  //   ]);
  // }

  // setLevelState(state, levelId) {
  //   let self = this;

  //   self.SuggestionBoxService.postActivateLevel(state, levelId).then(
  //     () => {
  //       let suggestionBox = self.$filter("translate")(
  //         "xhr.post_activate_level.success"
  //       );
  //       self.Flash.create("success", suggestionBox);
  //       self.tableParams.reload();
  //     },
  //     () => {
  //       let suggestionBox = self.$filter("translate")(
  //         "xhr.post_activate_level.error"
  //       );
  //       self.Flash.create("danger", suggestionBox);
  //     }
  //   );
  // }

  // /**
  //  * Deletes photo
  //  *
  //  * @method deletePhoto
  //  */
  // deletePhoto() {
  //   let self = this;

  //   this.SuggestionBoxService.deleteLevelImage(this.levelId)
  //     .then((res) => {
  //       self.$scope.levelImagePath = false;
  //       let suggestionBox = self.$filter("translate")(
  //         "xhr.delete_level_image.success"
  //       );
  //       self.Flash.create("success", suggestionBox);
  //     })
  //     .catch((err) => {
  //       self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
  //       let suggestionBox = self.$filter("translate")("xhr.delete_level_image.error");
  //       self.Flash.create("danger", suggestionBox);
  //     });
  // }

  // /**
  //  * Generating photo route
  //  *
  //  * @method generatePhotoRoute
  //  * @returns {string}
  //  */
  // generatePhotoRoute() {
  //   return this.config.apiUrl + "/level/" + this.levelId + "/photo";
  // }
}

SuggestionBoxController.$inject = [
  "$scope",
  "$state",
  "$timeout",
  "AuthService",
  "SuggestionBoxService",
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

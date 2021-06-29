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

  _getSuggestionBox() {
    let self = this;
    self.loaderStates.suggestionBoxDetails = true;
    if (self.suggestionBoxId) {
      self.SuggestionBoxService.getSuggestionBox(self.suggestionBoxId)
        .then(
          res => {
            self.$scope.suggestionBox = res;
            self.$scope.editableFields = self.EditableMap.humanizeCampaign(res);

            if (self.$scope.editableFields.levels && self.$scope.editableFields.levels.length) {
              let levels = self.$scope.editableFields.levels;
              for (let i in levels) {
                let level = _.find(self.levels, { id: levels[i] });
              }

            }
            if (self.$scope.editableFields.segments && self.$scope.editableFields.segments.length) {
              let segments = self.$scope.editableFields.segments;
              for (let i in segments) {
                let segment = _.find(self.segments, { id: segments[i] });
              }

            }
            if (self.$scope.editableFields.pos && self.$scope.editableFields.pos.length) {
              let poses = self.$scope.editableFields.pos;
              for (let i in poses) {
                let pos = _.find(self.pos, { id: poses[i] });
              }

            }
            self.$scope.editableFields = self.EditableMap.humanizeSuggestionBoxFields(res);
            self.loaderStates.suggestionBoxDetails = false;
          },
          () => {
            let message = self.$filter('translate')('xhr.get_suggestion_box.error');
            self.Flash.create('danger', message);
            self.loaderStates.suggestionBoxDetails = false;
          }
        );

      self.SuggestionBoxService.getSuggestionBoxImage(self.suggestionBoxId)
        .then(
          res => {
            self.$scope.suggestionBoxImagePath = true;
          }
        )
        .catch(
          err => {
            self.$scope.suggestionBoxImagePath = false;
          }
        );

    } else {
      self.$state.go('admin.suggestion-box-list');
      let message = self.$filter('translate')('xhr.get_suggestion_box.no_id');
      self.Flash.create('warning', message);
      self.loaderStates.suggestionBoxDetails = false;
    }
  }
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

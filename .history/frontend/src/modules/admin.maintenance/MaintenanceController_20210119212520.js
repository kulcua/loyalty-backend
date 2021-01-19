export default class MaintenanceController {
  constructor(
    $scope,
    MaintenanceService,
    Flash,
    NgTableParams,
    $q,
    ParamsMap,
    $filter,
    DataService,
    PaginationSettings
  ) {
    this.MaintenanceService = MaintenanceService;
    this.$scope = $scope;
    this.Flash = Flash;
    this.PaginationSettings = PaginationSettings;
    this.NgTableParams = NgTableParams;
    this.ParamsMap = ParamsMap;
    this.$q = $q;
    this.$filter = $filter;
    this.config = DataService.getConfig();

    this.loaderStates = {
      levelList: true,
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

          self.loaderStates.levelList = true;
          self.MaintenanceService.getLevels(
            self.ParamsMap.params(params.url())
          ).then(
            (res) => {
              self.$scope.levels = res;
              let realTotal = res.total;
              params.realTotal = () => realTotal;
              params.total(self.PaginationSettings.getTotal(res.total));
              self.loaderStates.levelList = false;
              self.loaderStates.coverLoader = false;
              dfd.resolve(res);
            },
            () => {
              let message = self.$filter("translate")(
                "xhr.get_levels_list.error"
              );
              self.Flash.create("danger", message);
              self.loaderStates.levelList = false;
              self.loaderStates.coverLoader = false;
              dfd.reject();
            }
          );

          return dfd.promise;
        },
      }
    );
  }
}

MaintenanceController.$inject = [
  "$scope",
  "MaintenanceService",
  "Flash",
  "NgTableParams",
  "$q",
  "ParamsMap",
  "$filter",
  "DataService",
  "PaginationSettings",
];

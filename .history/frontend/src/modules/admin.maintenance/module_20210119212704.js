import MaintenanceController from "./MaintenanceController";
import MaintenanceService from "./MaintenanceService";

const MODULE_NAME = "admin.maintenance";

angular
  .module(MODULE_NAME, [])
  .config(($stateProvider) => {
    $stateProvider.state("admin.mock-list", {
      url: "/mock-list",
      views: {
        // 'extendTop@': {
        //     templateUrl: 'templates/maintenance-list-extend-top.html',
        //     controller: 'MaintenanceController',
        //     controllerAs: 'MaintenanceCtrl'
        // },
        "main@": {
          templateUrl: require("./templates/mock-list.html"),
          controller: "MaintenanceController",
          controllerAs: "MaintenanceCtrl",
        },
        // 'extendBottom@': {
        //     templateUrl: 'templates/maintenance-list-extend-bottom.html',
        //     controller: 'MaintenanceController',
        //     controllerAs: 'MaintenanceCtrl'
        // }
      },
    });
  })
  .run(($templateCache, $http) => {
    let catchErrorTemplate = () => {
      throw `${MODULE_NAME} has missing template`;
    };
    // $templateCache.put('templates/maintenance-list-extend-bottom.html', '');
    // $templateCache.put('templates/maintenance-list-extend-top.html', '');

    $http
      .get(`templates/maintenance-list.html`)
      .then((response) => {
        $templateCache.put("templates/mock-list.html", response.data);
      })
      .catch(catchErrorTemplate);
  })
  .controller("MaintenanceController", MaintenanceController)
  .service("MaintenanceService", MaintenanceService);

try {
  window.OpenLoyaltyConfig.modules.push(MODULE_NAME);
} catch (err) {
  throw `${MODULE_NAME} will not be registered`;
}

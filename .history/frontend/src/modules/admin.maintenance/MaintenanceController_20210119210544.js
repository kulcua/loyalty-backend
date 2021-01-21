import MaintenanceController from "./MaintenanceController";
import MaintenanceService from "./MaintenanceService";

const MODULE_NAME = "admin.maintenance";

angular
  .module(MODULE_NAME, [])
  .config(($stateProvider) => {
    $stateProvider.state("admin.maintenance", {
      url: "/maintenance",
      views: {
        // "extendTop@": {
        //   templateUrl: "templates/maintenance-extend-top.html",
        //   controller: "MaintenanceController",
        //   controllerAs: "MockCtrl",
        // },
        "main@": {
          templateUrl: require("./templates/maintenance.html"),
          controller: "MaintenanceController",
          controllerAs: "MaintenanceCtrl",
        },
        // "extendBottom@": {
        //   templateUrl: "templates/maintenance-extend-bottom.html",
        //   controller: "MaintenanceController",
        //   controllerAs: "MockCtrl",
        // },
      },
    });
  })
  .run(($templateCache, $http) => {
    let catchErrorTemplate = () => {
      throw `${MODULE_NAME} has missing template`;
    };
    // $templateCache.put("templates/maintenance-extend-bottom.html", "");
    // $templateCache.put("templates/maintenance-extend-top.html", "");

    $http
      .get(`templates/mock-list.html`)
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

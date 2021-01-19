import MaintenanceController from "./MaintenanceController";
import MaintenanceService from "./MaintenanceService";

const MODULE_NAME = "admin.maintenances";

angular
  .module(MODULE_NAME, [])
  .config(($stateProvider) => {
    $stateProvider
      .state("admin.maintenances-list", {
        url: "/maintenances-list",
        views: {
          "extendTop@": {
            templateUrl: "templates/maintenances-list-extend-top.html",
            controller: "MaintenanceController",
            controllerAs: "MaintenanceCtrl",
          },
          "main@": {
            templateUrl: require("./templates/maintenances-list.html"),
            controller: "MaintenanceController",
            controllerAs: "MaintenanceCtrl",
          },
          "extendBottom@": {
            templateUrl: "templates/maintenances-list-extend-bottom.html",
            controller: "MaintenanceController",
            controllerAs: "MaintenanceCtrl",
          },
        },
      })
      .state("admin.add-maintenance", {
        url: "/add-maintenance",
        views: {
          "extendTop@": {
            templateUrl: "templates/add-maintenance-extend-top.html",
            controller: "MaintenanceController",
            controllerAs: "MaintenanceCtrl",
          },
          "main@": {
            templateUrl: require("./templates/add-maintenance.html"),
            controller: "MaintenanceController",
            controllerAs: "MaintenanceCtrl",
          },
          "extendBottom@": {
            templateUrl: "templates/add-maintenance-extend-bottom.html",
            controller: "MaintenanceController",
            controllerAs: "MaintenanceCtrl",
          },
        },
      })
      .state("admin.edit-maintenance", {
        url: "/edit-maintenance/:maintenanceId",
        views: {
          "extendTop@": {
            templateUrl: "templates/edit-maintenance-extend-top.html",
            controller: "MaintenanceController",
            controllerAs: "MaintenanceCtrl",
          },
          "main@": {
            templateUrl: require("./templates/edit-maintenance.html"),
            controller: "MaintenanceController",
            controllerAs: "MaintenanceCtrl",
          },
          "extendBottom@": {
            templateUrl: "templates/edit-maintenance-extend-bottom.html",
            controller: "MaintenanceController",
            controllerAs: "MaintenanceCtrl",
          },
        },
      });
  })
  .run(($templateCache, $http) => {
    let catchErrorTemplate = () => {
      throw `${MODULE_NAME} has missing template`;
    };

    $templateCache.put("templates/edit-maintenance-extend-bottom.html", "");
    $templateCache.put("templates/edit-maintenance-extend-top.html", "");

    $templateCache.put("templates/add-maintenance-extend-bottom.html", "");
    $templateCache.put("templates/add-maintenance-extend-top.html", "");

    $templateCache.put("templates/maintenances-list-extend-bottom.html", "");
    $templateCache.put("templates/maintenances-list-extend-top.html", "");

    $http
      .get(`templates/maintenances-list.html`)
      .then((response) => {
        $templateCache.put("templates/maintenances-list.html", response.data);
      })
      .catch(catchErrorTemplate);

    $http
      .get(`templates/add-maintenance.html`)
      .then((response) => {
        $templateCache.put("templates/add-maintenance.html", response.data);
      })
      .catch(catchErrorTemplate);

    $http
      .get(`templates/edit-maintenance.html`)
      .then((response) => {
        $templateCache.put("templates/edit-maintenance.tml", response.data);
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

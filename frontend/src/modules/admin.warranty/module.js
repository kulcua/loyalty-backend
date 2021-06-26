import WarrantyController from "./WarrantyController";
import WarrantyService from "./WarrantyService";

const MODULE_NAME = "admin.warranty";

angular
  .module(MODULE_NAME, [])
  .config(($stateProvider) => {
    $stateProvider
      .state("admin.warranties-list", {
        url: "/warranties-list",
        views: {
          "extendTop@": {
            templateUrl: "templates/warranties-list-extend-top.html",
            controller: "WarrantyController",
            controllerAs: "WarrantyCtrl",
          },
          "main@": {
            templateUrl: require("./templates/warranties-list.html"),
            controller: "WarrantyController",
            controllerAs: "WarrantyCtrl",
          },
          "extendBottom@": {
            templateUrl: "templates/warranties-list-extend-bottom.html",
            controller: "WarrantyController",
            controllerAs: "WarrantyCtrl",
          },
        },
      })
      .state("admin.add-warranty", {
        url: "/add-warranty",
        views: {
          "extendTop@": {
            templateUrl: "templates/add-warranty-extend-top.html",
            controller: "WarrantyController",
            controllerAs: "WarrantyCtrl",
          },
          "main@": {
            templateUrl: require("./templates/add-warranty.html"),
            controller: "WarrantyController",
            controllerAs: "WarrantyCtrl",
          },
          "extendBottom@": {
            templateUrl: "templates/add-warranty-extend-bottom.html",
            controller: "WarrantyController",
            controllerAs: "WarrantyCtrl",
          },
        },
      })
      .state("admin.edit-warranty", {
        url: "/edit-warranty/:warrantyId",
        views: {
          "extendTop@": {
            templateUrl: "templates/edit-warranty-extend-top.html",
            controller: "WarrantyController",
            controllerAs: "WarrantyCtrl",
          },
          "main@": {
            templateUrl: require("./templates/edit-warranty.html"),
            controller: "WarrantyController",
            controllerAs: "WarrantyCtrl",
          },
          "extendBottom@": {
            templateUrl: "templates/edit-warranty-extend-bottom.html",
            controller: "WarrantyController",
            controllerAs: "WarrantyCtrl",
          },
        },
      });
  })
  .run(($templateCache, $http) => {
    let catchErrorTemplate = () => {
      throw `${MODULE_NAME} has missing template`;
    };

    $templateCache.put("templates/edit-warranty-extend-bottom.html", "");
    $templateCache.put("templates/edit-warranty-extend-top.html", "");

    $templateCache.put("templates/add-warranty-extend-bottom.html", "");
    $templateCache.put("templates/add-warranty-extend-top.html", "");

    $templateCache.put("templates/warranties-list-extend-bottom.html", "");
    $templateCache.put("templates/warranties-list-extend-top.html", "");

    $http
      .get(`templates/warranties-list.html`)
      .then((response) => {
        $templateCache.put("templates/warranties-list.html", response.data);
      })
      .catch(catchErrorTemplate);

    $http
      .get(`templates/add-warranty.html`)
      .then((response) => {
        $templateCache.put("templates/add-warranty.html", response.data);
      })
      .catch(catchErrorTemplate);

    $http
      .get(`templates/edit-warranty.html`)
      .then((response) => {
        $templateCache.put("templates/edit-warranty.tml", response.data);
      })
      .catch(catchErrorTemplate);
  })
  .controller("WarrantyController", WarrantyController)
  .service("WarrantyService", WarrantyService);

try {
  window.OpenLoyaltyConfig.modules.push(MODULE_NAME);
} catch (err) {
  throw `${MODULE_NAME} will not be registered`;
}

import RequestController from "./RequestController";
import RequestService from "./RequestService";

const MODULE_NAME = "admin.askforhelp";

angular
  .module(MODULE_NAME, [])
  .config(($stateProvider) => {
    $stateProvider
      .state("admin.request-list", {
        url: "/request-list",
        views: {
          "extendTop@": {
            templateUrl: "templates/request-list-extend-top.html",
            controller: "RequestController",
            controllerAs: "RequestCtrl",
          },
          "main@": {
            templateUrl: require("./templates/request-list.html"),
            controller: "RequestController",
            controllerAs: "RequestCtrl",
          },
          "extendBottom@": {
            templateUrl: "templates/request-list-extend-bottom.html",
            controller: "RequestController",
            controllerAs: "RequestCtrl",
          },
        },
      })
  })
  .run(($templateCache, $http) => {
    let catchErrorTemplate = () => {
      throw `${MODULE_NAME} has missing template`;
    };

    $templateCache.put("templates/request-list-extend-bottom.html", "");
    $templateCache.put("templates/request-list-extend-top.html", "");


    $http
      .get(`templates/request-list.html`)
      .then((response) => {
        $templateCache.put("templates/request-list.html", response.data);
      })
      .catch(catchErrorTemplate);

  })
  .controller("RequestController", RequestController)
  .service("RequestService", RequestService);

try {
  window.OpenLoyaltyConfig.modules.push(MODULE_NAME);
} catch (err) {
  throw `${MODULE_NAME} will not be registered`;
}

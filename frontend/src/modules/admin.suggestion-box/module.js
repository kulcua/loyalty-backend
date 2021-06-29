import SuggestionBoxController from "./SuggestionBoxController";
import SuggestionBoxService from "./SuggestionBoxService";

const MODULE_NAME = "admin.suggestion-box";

angular
  .module(MODULE_NAME, [])
  .config(($stateProvider) => {
    $stateProvider
      .state("admin.suggestion-box-list", {
        url: "/suggestion-box-list",
        views: {
          "extendTop@": {
            templateUrl: "templates/suggestion-box-list-extend-top.html",
            controller: "SuggestionBoxController",
            controllerAs: "SuggestionBoxCtrl",
          },
          "main@": {
            templateUrl: require("./templates/suggestion-box-list.html"),
            controller: "SuggestionBoxController",
            controllerAs: "SuggestionBoxCtrl",
          },
          "extendBottom@": {
            templateUrl: "templates/suggestion-box-list-extend-bottom.html",
            controller: "SuggestionBoxController",
            controllerAs: "SuggestionBoxCtrl",
          },
        },
      })
  })
  .run(($templateCache, $http) => {
    let catchErrorTemplate = () => {
      throw `${MODULE_NAME} has missing template`;
    };

    $templateCache.put("templates/suggestion-box-list-extend-bottom.html", "");
    $templateCache.put("templates/suggestion-box-list-extend-top.html", "");


    $http
      .get(`templates/suggestion-box-list.html`)
      .then((response) => {
        $templateCache.put("templates/suggestion-box-list.html", response.data);
      })
      .catch(catchErrorTemplate);

  })
  .controller("SuggestionBoxController", SuggestionBoxController)
  .service("SuggestionBoxService", SuggestionBoxService);

try {
  window.OpenLoyaltyConfig.modules.push(MODULE_NAME);
} catch (err) {
  throw `${MODULE_NAME} will not be registered`;
}

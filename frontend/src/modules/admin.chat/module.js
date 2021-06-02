import ChatController from "./ChatController";
import ChatService from "./ChatService";

const MODULE_NAME = "admin.chat";

angular
  .module(MODULE_NAME, [])
  .factory("Ratchet", function () {
    return new WebSocket('ws://localhost:8080');
})
  .config(($stateProvider) => {
    $stateProvider
      .state("admin.chat", {
        url: "/chat",
        views: {
          "main@": {
            templateUrl: require("./templates/chat.html"),
            controller: "ChatController",
            controllerAs: "ChatCtrl",
          },
        }
      })
      .state("admin.chat.chat-screen", {
        url: "/chat-screen",
        views: {
          "mess": {
            templateUrl: require("./templates/chat-screen.html"),
            controller: "ChatController",
            controllerAs: "ChatCtrl",
          },
        },
        params: {
          conversation: null,
        },
      });
  })
  .run(($templateCache, $http) => {
    let catchErrorTemplate = () => {
      throw `${MODULE_NAME} has missing template`;
    };

    $http
      .get(`templates/chat.html`)
      .then((response) => {
        $templateCache.put("templates/chat.html", response.data);
      })
      .catch(catchErrorTemplate);

    $http
      .get(`templates/chat-screen.html`)
      .then((response) => {
        $templateCache.put("templates/chat-screen.html", response.data);
      })
      .catch(catchErrorTemplate);
  })
  .controller("ChatController", ChatController)
  .service("ChatService", ChatService);

try {
  window.OpenLoyaltyConfig.modules.push(MODULE_NAME);
} catch (err) {
  throw `${MODULE_NAME} will not be registered`;
}

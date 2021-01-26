// import ChatService from "./ChatService";

import moment from "moment";

export default class ChatController {
  constructor(
    $scope,
    $state,
    $timeout,
    AuthService,
    ChatService,
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
    this.ChatService = ChatService;
    this.$state = $state;
    this.conversation = $stateParams.conversation || null;
    this.AuthService = AuthService;
    this.$scope.loggedUserId = AuthService.getLoggedUserId() || null;
    this.Flash = Flash;
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

    this.loaderStates = {
      chatList: true,
      coverLoader: true,
    };
  }

  getData() {
    let self = this;
    let dfd = self.$q.defer();
    self.loaderStates.coverLoader = false;

    self.loaderStates.chatList = true;
    self.ChatService.getCustomerConversation().then(
      () => {
        self.$scope.conversations = self.ChatService.getConversations();
        self.loaderStates.chatList = false;
        self.loaderStates.coverLoader = false;
      },
      () => {
        let message = self.$filter("translate")("xhr.get_settings.error");
        self.Flash.create("danger", message);
        self.loaderStates.chatList = false;
        self.loaderStates.coverLoader = false;
      }
    );

    return dfd.promise;
  }

  getMessages() {
    let self = this;
    if (self.conversation.lastMessageSnippet) {
      self.ChatService.getMessages(
        self.conversation.conversationId.conversationId
      ).then(
        () => {
          self.$scope.messages = self.ChatService.messages;
          self.loaderStates.coverLoader = false;
        },
        () => {
          let message = self.$filter("translate")("xhr.get_message_list.error");
          self.Flash.create("danger", message);
          self.loaderStates.coverLoader = false;
        }
      );
    } else {
      self.$state.go("admin.chat");
      let message = self.$filter("translate")("xhr.get_single_chat.no_id");
      self.Flash.create("warning", message);
      self.loaderStates.coverLoader = false;
    }
  }

  addChat(newMess) {
    let self = this;
    let editedConversation = angular.copy(self.conversation);

    newMess.conversationId = self.conversation.conversationId.conversationId;
    newMess.senderName = "admin";
    newMess.senderId = self.$scope.loggedUserId;
    newMess.messageTimestamp = moment(Date.now()).format(
      self.config.dateFormat
    );

    editedConversation.lastMessageSnippet = newMess.message;
    editedConversation.lastMessageTimestamp = moment(Date.now()).format(
      self.config.dateFormat
    );

    self.ChatService.postChat(newMess).then(
      () => {
        if (self.conversation.conversationId.conversationId) {
          self.ChatService.putConversation(
            self.conversation.conversationId.conversationId,
            editedConversation
          )
            .then(
              () => {
                let message = self.$filter("translate")(
                  "xhr.put_conversation.success"
                );
                self.Flash.create("success", message);
              },
              (res) => {
                self.$scope.validate = self.Validation.mapSymfonyValidation(
                  res.data
                );
                let message = self.$filter("translate")(
                  "xhr.put_conversation.error"
                );
                self.Flash.create("danger", message);
              }
            )
            .catch((err) => {
              self.$scope.fileValidate = self.Validation.mapSymfonyValidation(
                err.data
              );
              self.ChatService.storedFileError = self.$scope.fileValidate;

              let message = self.$filter("translate")(
                "xhr.post_single_chat.warning"
              );
              self.Flash.create("warning", message);
            });
        } else {
          let message = self.$filter("translate")(
            "xhr.post_single_chat.success"
          );
          self.Flash.create("success", message);
        }
      },
      (res) => {
        self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
        let message = self.$filter("translate")("xhr.post_single_chat.error");
        self.Flash.create("danger", message);
      }
    );
  }
}

ChatController.$inject = [
  "$scope",
  "$state",
  "$timeout",
  "AuthService",
  "ChatService",
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

import moment from "moment";
import * as $ from 'jquery' 

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
      self.config.dateTimeFormat
    );

    editedConversation.lastMessageSnippet = newMess.message;
    editedConversation.lastMessageTimestamp = moment(Date.now()).format(
      self.config.dateTimeFormat
    );

     if((newMess.message)&&(newMess.message.trim()!=''))
     {
      self.ChatService.postChat(newMess).then(
        () => {
          if (self.conversation.conversationId.conversationId) {
            self.ChatService.putConversation(
              self.conversation.conversationId.conversationId,
              editedConversation
            )
              .then(
                () => {
                  newMess.message = "";
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

  appendJquery(content)
  {
    let user_id = this.$scope.loggedUserId;
    let mess = content;

    let data = {
        userId : user_id,
        msg: mess
    }

    if((content)&&(content.trim()!=''))
    {
      conn.send(JSON.stringify(data));
    }
  }
}

var conn = new WebSocket('ws://localhost:8080');

conn.onopen = function(e) {
  console.log("Connection established!");
};

conn.onmessage = function(e) { 
  console.log(e.data);
  let data = JSON.parse(e.data);
  var classname = "chat-bubble chat-bubble--right";
  $('#phantudiv').append("<div class="+classname+"> "+data.msg+" </div>");
  $('#chat-panel').animate({scrollTop: $('#chat-panel')[0].scrollHeight}, 2000);
  document.getElementById("chat-form").reset();
};

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

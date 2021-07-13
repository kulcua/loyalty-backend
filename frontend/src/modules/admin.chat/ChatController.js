import moment from "moment";
import * as $ from 'jquery'
import RootController from "../../component/global/root/RootController";

export default class ChatController {
  constructor(
    $scope,
    Ratchet,
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
    this.Ratchet = Ratchet;
    this.ChatService = ChatService;
    this.$state = $state;
    this.conversation = $stateParams.conversation || null;
    this.AuthService = AuthService;
    this.$scope.loggedUserId = AuthService.getLoggedUserId() || null;
    this.$scope.firstSelected = null;
    this.$scope.isImage = false;
    this.$scope.image=null;
    this.Flash = Flash;
    this.NgTableParams = NgTableParams;
    this.$scope.selectedIndex = 0;
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
    this.settingWebServer(this.$scope.loggedUserId, this.ChatService,this.$scope);

  }

  settingWebServer(loggedId, chatService,scope) {
    this.Ratchet.onopen = function (e) {
      console.log("Connection established!");
    };
    this.Ratchet.onmessage = function (e) {
      let data = JSON.parse(e.data);

      let content;
      let classname;
      let id;
      let receiverId = data.customerId;
      let senderId = data.userId;

        //add text chat
        if (data.from == 'Me') {
          classname = "chat-bubble chat-bubble--right";
          id = receiverId;
        } else {
          classname = "chat-left";
          id = senderId;
        }

        if(data.type=="text")
        {
        chatService.Restangular.one('chat', data.messId).get().then((value) => {
        let time = moment(Date.now()).format(
         "HH:mm"
        );

         //update new mess
         $('#cusid_' + id).html('<span class="dot"></span>');
         $('#lastMess_' + id).html('<p class="text-muted">'+value.message+' </p>');
         $('#lastTime_' + id).html('<span class="time text-muted small">' +time+ '</span');
   
         $('#chat-text' + id).append("<div class=" + classname + "> " + value.message + " </div>");
         $('#chat-panel').animate({
           scrollTop: $('#chat-panel')[0].scrollHeight
         }, 2000);
         
   
         //reset text box
         document.getElementById("chat-form").reset();
        }) 
      }else {
        chatService.Restangular.one('chat').one('message').one('photo', data.messId).get().then((value) => {
          let time = moment(Date.now()).format(
           "HH:mm"
          );
  
           //update new mess
           $('#cusid_' + id).html('<span class="dot"></span>');
           $('#lastMess_' + id).html('<p class="text-muted"> photo </p>');
           $('#lastTime_' + id).html('<span class="time text-muted small">' +time+ '</span');
     
          $('#chat-text' + id).append("<div class=" + classname + "> <img src="+URL.createObjectURL(scope.image)+"  width='300'  height='300' >  </div>");
          
          $('#chat-panel').animate({
             scrollTop: $('#chat-panel')[0].scrollHeight
           }, 2000);
           
           scope.image = null;
           //reset text box
           document.getElementById("chat-form").reset();})
      }
    }

  }


  fileUpload() {
    imgInp.onchange = evt => {
      const [file] = imgInp.files
      if (file) {
        $("#selectedImage").attr("src", URL.createObjectURL(file));
        this.$scope.image = file;
        $("#inputText").prop('disabled', true);
      }
    }
    
    this.$scope.isImage = true;

  };

  getData() {
    let self = this;
    let dfd = self.$q.defer();
    self.loaderStates.coverLoader = false;

    self.loaderStates.chatList = true;
    self.ChatService.getCustomerConversation().then(
      () => {
        self.$scope.conversations = self.ChatService.getConversations();
        self.$scope.firstSelected = self.$scope.conversations[0];
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
    this.$scope.customerName = self.conversation.participantNames[1];

    if (self.conversation.lastMessageSnippet) {
      self.ChatService.getMessages(
        self.conversation.conversationId.conversationId
      ).then(
        () => {
          self.$scope.messages = self.ChatService.messages;
          $('#chat-panel').animate({
            scrollTop: $('#chat-panel')[0].scrollHeight
          }, 2000);
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


      if(self.$scope.isImage)
      {
      newMess.file = self.$scope.image;
      self.ChatService.postImage(newMess).then((value) => {

        this.appendJquery(value.messageId);


        if (self.conversation.conversationId.conversationId) {
          self.ChatService.putConversation(
              self.conversation.conversationId.conversationId,
              editedConversation
            )
            .then(
              () => {
                newMess.message = "";
                $("#inputText").prop('disabled', false);
                self.$scope.isImage = false;
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

      }, (res) => {
        self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
        let message = self.$filter("translate")("xhr.post_single_chat.error");
        self.Flash.create("danger", message);
      })
    }
    else //post chat
    {
      if (newMess.message) {
      self.ChatService.postChat(newMess).then((value) => {

        this.appendJquery(value.messageId);


        if (self.conversation.conversationId.conversationId) {
          self.ChatService.putConversation(
              self.conversation.conversationId.conversationId,
              editedConversation
            )
            .then(
              () => {
                newMess.message = "";
                self.$scope.isImage = false;
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

      }, (res) => {
        self.$scope.validate = self.Validation.mapSymfonyValidation(res.data);
        let message = self.$filter("translate")("xhr.post_single_chat.error");
        self.Flash.create("danger", message);
      })
    }
    }
  }

  appendJquery(messId) {

    let user_id = this.$scope.loggedUserId; //truyen day ne
    let cusId = this.conversation.participantIds[1];
    let type = "text";
    if(this.$scope.image!=null) {
      type = "media"
    }

    let data = {
      userId: user_id,
      customerId: cusId,
      messId: messId,
      type: type,
    }

    this.Ratchet.send(JSON.stringify(data));

  }

  highlightName(index, conversation) {
    this.$scope.selectedIndex = index;
    this.$scope.conversation = conversation;
    this.$scope.customerName = conversation.participantNames[1];
    this.$scope.customerId = conversation.participantIds[1];
    $('#cusid_' + conversation.participantIds[1]).html('');
  }
}

ChatController.$inject = [
  "$scope",
  "Ratchet",
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
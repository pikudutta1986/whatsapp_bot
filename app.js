/*
 * Starter Project for WhatsApp Echo Bot Tutorial
 *
 * Remix this as the starting point for following the WhatsApp Echo Bot tutorial
 *
 */

"use strict";

// Access token for your app
// (copy token from DevX getting started page
// and save it as environment variable into the .env file)
const token = process.env.WHATSAPP_TOKEN;

// Imports dependencies and set up http server
const request = require("request"),
  express = require("express"),
  body_parser = require("body-parser"),
  axios = require("axios").default,
  app = express().use(body_parser.json()); // creates express http server

// Sets server port and logs message on success
app.listen(process.env.PORT || 1337, () => console.log("webhook is listening"));

// Accepts POST requests at /webhook endpoint
app.post("/webhook", (req, res) => {
  // Parse the request body from the POST
  let body = req.body;
  
  // console.log(token,'token');

  // Check the Incoming webhook message
  console.log(JSON.stringify(req.body, null, 2));
  // console.log(JSON.stringify(req.body.entry[0].changes[0].value.messages[0].interactive.button_reply.id));

  // info on WhatsApp text message payload: https://developers.facebook.com/docs/whatsapp/cloud-api/webhooks/payload-examples#text-messages
  if (req.body.object) {
    if (
      req.body.entry &&
      req.body.entry[0].changes &&
      req.body.entry[0].changes[0] &&
      req.body.entry[0].changes[0].value.messages &&
      req.body.entry[0].changes[0].value.messages[0]
    ) {
      let phone_number_id =
        req.body.entry[0].changes[0].value.metadata.phone_number_id;
      let from = req.body.entry[0].changes[0].value.messages[0].from; // extract the phone number from the webhook payload
      
      let msg_body = '';
      
      if(typeof req.body.entry[0].changes[0].value.messages[0].text !== "undefined") {
        msg_body = req.body.entry[0].changes[0].value.messages[0].text.body; // extract the message text from the webhook payload        
      } else if(typeof req.body.entry[0].changes[0].value.messages[0].interactive.button_reply !== "undefined") {
        msg_body = req.body.entry[0].changes[0].value.messages[0].interactive.button_reply.id; // extract the message text from the webhook payload        
      } else if(typeof req.body.entry[0].changes[0].value.messages[0].interactive.list_reply !== "undefined") {
        msg_body = req.body.entry[0].changes[0].value.messages[0].interactive.list_reply.id;
      }
      
      console.log(msg_body,'msg_body');
      console.log(from,'from');
      console.log(phone_number_id,'phone_number_id');
      
      // if(phone_number_id) {
      //   console.log('call another api');
      // }
      // axios({
      //   method: "POST", // Required, HTTP method, a string, e.g. POST, GET
      //   url:
      //     "https://graph.facebook.com/v15.0/" +
      //     phone_number_id +
      //     "/messages?access_token=" +
      //     token,
      //   data: {
      //     messaging_product: "whatsapp",
      //     to: from,
      //     text: { body: "Ack: " + msg_body },
      //   },
      //   headers: { "Content-Type": "application/json" },
      // });
      
      const headers = {
        "Accept": "*/*",
        // "Host": "graph.facebook.com",
        "Content-Type": "application/json",
        "Accept-Encoding": "gzip, deflate, br",
        "Connection": "keep-alive",
     };
      
      if(msg_body != '') {
          axios({
              method: "POST", // Required, HTTP method, a string, e.g. POST, GET
              url:
                "http://appzone.in/webhook.php",
              data: {
                "messaging_product": "whatsapp",
                "to": from,       
                "msg_body" : msg_body
              },
              headers: headers,
          }).then((response) => {
            console.log(response.data,'response');
          }, (error) => {
            console.log('error');
          });        
      }
      
      
    } else {
      res.sendStatus(200);
    }
    
  } else {
    // Return a '404 Not Found' if event is not from a WhatsApp API
    res.sendStatus(404);
  }
});

// Accepts GET requests at the /webhook endpoint. You need this URL to setup webhook initially.
// info on verification request payload: https://developers.facebook.com/docs/graph-api/webhooks/getting-started#verification-requests 
app.get("/webhook", (req, res) => {
  /**
   * UPDATE YOUR VERIFY TOKEN
   *This will be the Verify Token value when you set up webhook
  **/
  const verify_token = "appzone";

  // Parse params from the webhook verification request
  let mode = req.query["hub.mode"];
  let token = req.query["hub.verify_token"];
  let challenge = req.query["hub.challenge"];

  // Check if a token and mode were sent
  if (mode && token) {
    // Check the mode and token sent are correct
    if (mode === "subscribe" && token === verify_token) {
      // Respond with 200 OK and challenge token from the request
      console.log("WEBHOOK_VERIFIED");
      res.status(200).send(challenge);
    } else {
      // Responds with '403 Forbidden' if verify tokens do not match
      res.sendStatus(403);
    }
  }
});

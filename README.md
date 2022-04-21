# Chat App
Tech stack:
1) PHP + mysql - Chat API (this repository)
2) NodeJS + express + socket.io + typescript - web socket server for realtime messaging
3) React + Redux Toolkit + Material UI + socket.io-client + typescript - web chat client

Client and WS server you can find here https://github.com/itmaxxx/chat-app

## What I have already done
Implemented a PHP API for chat, with support for authorization, chat rooms, access rights. I wrote my own small utility for tests like jest from nodejs. Using it, I wrote tests for all e2e endpoints (attached screenshots). To do this, I made a class for working with the database, which seeded the database with prepared fixtures.

On NodeJS, I implemented a websocket server for realtime chat, under the hood it accesses the PHP API.

On ReactJS, I implemented a client for a chat, with support for authorization, and realtime correspondence in different chats.

# Preview

[![Chat app preview](https://img.youtube.com/vi/gUgklSGr6RQ/0.jpg)](https://www.youtube.com/watch?v=gUgklSGr6RQ)

# encoding: UTF-8
# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended that you check this file into your version control system.

ActiveRecord::Schema.define(version: 20161031165929) do

  create_table "active_admin_comments", force: :cascade do |t|
    t.string   "namespace"
    t.text     "body"
    t.string   "resource_id",   null: false
    t.string   "resource_type", null: false
    t.integer  "author_id"
    t.string   "author_type"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  add_index "active_admin_comments", ["author_type", "author_id"], name: "index_active_admin_comments_on_author_type_and_author_id"
  add_index "active_admin_comments", ["namespace"], name: "index_active_admin_comments_on_namespace"
  add_index "active_admin_comments", ["resource_type", "resource_id"], name: "index_active_admin_comments_on_resource_type_and_resource_id"

  create_table "admin_users", force: :cascade do |t|
    t.string   "email",                  default: "", null: false
    t.string   "encrypted_password",     default: "", null: false
    t.string   "reset_password_token"
    t.datetime "reset_password_sent_at"
    t.datetime "remember_created_at"
    t.integer  "sign_in_count",          default: 0,  null: false
    t.datetime "current_sign_in_at"
    t.datetime "last_sign_in_at"
    t.string   "current_sign_in_ip"
    t.string   "last_sign_in_ip"
    t.datetime "created_at",                          null: false
    t.datetime "updated_at",                          null: false
  end

  add_index "admin_users", ["email"], name: "index_admin_users_on_email", unique: true
  add_index "admin_users", ["reset_password_token"], name: "index_admin_users_on_reset_password_token", unique: true

  create_table "articles", force: :cascade do |t|
    t.string   "title"
    t.text     "body"
    t.string   "category"
    t.datetime "published_at"
    t.boolean  "active"
    t.string   "cover"
    t.integer  "user_id"
    t.datetime "created_at",   null: false
    t.datetime "updated_at",   null: false
  end

  add_index "articles", ["user_id"], name: "index_articles_on_user_id"

  create_table "credits", force: :cascade do |t|
    t.integer  "user_id"
    t.datetime "expired_at"
    t.integer  "exchange_id"
    t.datetime "created_at",          null: false
    t.datetime "updated_at",          null: false
    t.integer  "toy_id"
    t.integer  "used_in_exchange_id"
  end

  add_index "credits", ["exchange_id"], name: "index_credits_on_exchange_id"
  add_index "credits", ["toy_id"], name: "index_credits_on_toy_id"
  add_index "credits", ["user_id"], name: "index_credits_on_user_id"

  create_table "exchange_messages", force: :cascade do |t|
    t.integer  "user_id"
    t.integer  "exchange_id"
    t.integer  "user_from"
    t.integer  "user_to"
    t.text     "message"
    t.datetime "read_at"
    t.datetime "created_at",  null: false
    t.datetime "updated_at",  null: false
  end

  add_index "exchange_messages", ["exchange_id"], name: "index_exchange_messages_on_exchange_id"
  add_index "exchange_messages", ["user_id"], name: "index_exchange_messages_on_user_id"

  create_table "exchanges", force: :cascade do |t|
    t.integer  "toy_from"
    t.integer  "toy_to"
    t.string   "exchange_type"
    t.string   "exchange_deliver"
    t.integer  "rating_from"
    t.integer  "rating_to"
    t.boolean  "finalized"
    t.datetime "finalized_at"
    t.boolean  "accepted"
    t.integer  "user_id"
    t.datetime "created_at",         null: false
    t.datetime "updated_at",         null: false
    t.string   "reason"
    t.boolean  "credit_offer"
    t.integer  "user_to"
    t.datetime "user_from_received"
    t.datetime "user_to_received"
    t.datetime "deleted_at"
  end

  add_index "exchanges", ["user_id"], name: "index_exchanges_on_user_id"

  create_table "mailboxer_conversation_opt_outs", force: :cascade do |t|
    t.integer "unsubscriber_id"
    t.string  "unsubscriber_type"
    t.integer "conversation_id"
  end

  add_index "mailboxer_conversation_opt_outs", ["conversation_id"], name: "index_mailboxer_conversation_opt_outs_on_conversation_id"
  add_index "mailboxer_conversation_opt_outs", ["unsubscriber_id", "unsubscriber_type"], name: "index_mailboxer_conversation_opt_outs_on_unsubscriber_id_type"

  create_table "mailboxer_conversations", force: :cascade do |t|
    t.string   "subject",    default: ""
    t.datetime "created_at",              null: false
    t.datetime "updated_at",              null: false
  end

  create_table "mailboxer_notifications", force: :cascade do |t|
    t.string   "type"
    t.text     "body"
    t.string   "subject",              default: ""
    t.integer  "sender_id"
    t.string   "sender_type"
    t.integer  "conversation_id"
    t.boolean  "draft",                default: false
    t.string   "notification_code"
    t.integer  "notified_object_id"
    t.string   "notified_object_type"
    t.string   "attachment"
    t.datetime "updated_at",                           null: false
    t.datetime "created_at",                           null: false
    t.boolean  "global",               default: false
    t.datetime "expires"
  end

  add_index "mailboxer_notifications", ["conversation_id"], name: "index_mailboxer_notifications_on_conversation_id"
  add_index "mailboxer_notifications", ["notified_object_id", "notified_object_type"], name: "index_mailboxer_notifications_on_notified_object_id_and_type"
  add_index "mailboxer_notifications", ["sender_id", "sender_type"], name: "index_mailboxer_notifications_on_sender_id_and_sender_type"
  add_index "mailboxer_notifications", ["type"], name: "index_mailboxer_notifications_on_type"

  create_table "mailboxer_receipts", force: :cascade do |t|
    t.integer  "receiver_id"
    t.string   "receiver_type"
    t.integer  "notification_id",                            null: false
    t.boolean  "is_read",                    default: false
    t.boolean  "trashed",                    default: false
    t.boolean  "deleted",                    default: false
    t.string   "mailbox_type",    limit: 25
    t.datetime "created_at",                                 null: false
    t.datetime "updated_at",                                 null: false
  end

  add_index "mailboxer_receipts", ["notification_id"], name: "index_mailboxer_receipts_on_notification_id"
  add_index "mailboxer_receipts", ["receiver_id", "receiver_type"], name: "index_mailboxer_receipts_on_receiver_id_and_receiver_type"

  create_table "orders", force: :cascade do |t|
    t.string   "code"
    t.string   "title"
    t.string   "price"
    t.string   "status"
    t.integer  "user_id"
    t.integer  "toy_id"
    t.datetime "created_at"
    t.datetime "updated_at"
  end

  add_index "orders", ["user_id"], name: "index_orders_on_user_id"

  create_table "places", force: :cascade do |t|
    t.string   "title"
    t.text     "description"
    t.string   "office_hours"
    t.string   "phone"
    t.string   "phone_alt"
    t.integer  "user_id"
    t.string   "zipcode"
    t.string   "street"
    t.string   "city"
    t.string   "state"
    t.float    "latitude"
    t.float    "longitude"
    t.datetime "created_at",   null: false
    t.datetime "updated_at",   null: false
    t.boolean  "is_active"
    t.string   "complement"
    t.string   "neighborhood"
  end

  add_index "places", ["user_id"], name: "index_places_on_user_id"

  create_table "taggings", force: :cascade do |t|
    t.integer  "tag_id"
    t.integer  "taggable_id"
    t.string   "taggable_type"
    t.integer  "tagger_id"
    t.string   "tagger_type"
    t.string   "context",       limit: 128
    t.datetime "created_at"
  end

  add_index "taggings", ["tag_id", "taggable_id", "taggable_type", "context", "tagger_id", "tagger_type"], name: "taggings_idx", unique: true
  add_index "taggings", ["taggable_id", "taggable_type", "context"], name: "index_taggings_on_taggable_id_and_taggable_type_and_context"

  create_table "tags", force: :cascade do |t|
    t.string  "name"
    t.integer "taggings_count", default: 0
  end

  add_index "tags", ["name"], name: "index_tags_on_name", unique: true

  create_table "toy_ages", force: :cascade do |t|
    t.string   "title"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "toy_categories", force: :cascade do |t|
    t.string   "title"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "toy_images", force: :cascade do |t|
    t.integer  "toy_id"
    t.string   "image"
    t.boolean  "featured",   default: false
    t.datetime "created_at",                 null: false
    t.datetime "updated_at",                 null: false
  end

  add_index "toy_images", ["toy_id"], name: "index_toy_images_on_toy_id"

  create_table "toys", force: :cascade do |t|
    t.string   "title"
    t.text     "description"
    t.integer  "toy_category_id"
    t.integer  "toy_age_id"
    t.integer  "user_id"
    t.string   "zipcode"
    t.float    "latitude"
    t.float    "longitude"
    t.datetime "created_at",                       null: false
    t.datetime "updated_at",                       null: false
    t.string   "image"
    t.boolean  "is_active"
    t.datetime "deleted_at"
    t.string   "neighborhood"
    t.date     "next_notification_at"
    t.datetime "expired_at"
    t.integer  "activate_qty",         default: 0
  end

  add_index "toys", ["toy_age_id"], name: "index_toys_on_toy_age_id"
  add_index "toys", ["toy_category_id"], name: "index_toys_on_toy_category_id"
  add_index "toys", ["user_id"], name: "index_toys_on_user_id"

  create_table "user_children", force: :cascade do |t|
    t.string  "name"
    t.date    "birthday"
    t.string  "gender"
    t.integer "user_id"
  end

  add_index "user_children", ["user_id"], name: "index_user_children_on_user_id"

  create_table "users", force: :cascade do |t|
    t.string   "email",                  default: "",    null: false
    t.string   "encrypted_password",     default: "",    null: false
    t.string   "reset_password_token"
    t.datetime "reset_password_sent_at"
    t.datetime "remember_created_at"
    t.integer  "sign_in_count",          default: 0,     null: false
    t.datetime "current_sign_in_at"
    t.datetime "last_sign_in_at"
    t.string   "current_sign_in_ip"
    t.string   "last_sign_in_ip"
    t.datetime "created_at",                             null: false
    t.datetime "updated_at",                             null: false
    t.string   "username"
    t.string   "name"
    t.date     "birthday"
    t.string   "phone"
    t.string   "gender"
    t.string   "street"
    t.string   "city"
    t.string   "state"
    t.string   "zipcode"
    t.float    "latitude"
    t.float    "longitude"
    t.string   "avatar"
    t.datetime "deleted_at"
    t.string   "complement"
    t.string   "neighborhood"
    t.boolean  "newsletter"
    t.boolean  "admin",                  default: false
  end

  add_index "users", ["email"], name: "index_users_on_email", unique: true
  add_index "users", ["reset_password_token"], name: "index_users_on_reset_password_token", unique: true

end

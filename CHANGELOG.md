# Changelog

All notable changes to `wp-settings` will be documented in this file

## Unreleased

# 1.2.1 - 2023-08-23

- Use `sanitize_textarea_field` for the `textarea` option (#14)

# 1.2.0 - 2023-08-09

- Add media option type (and image and video)
- Add `option_level` on sections and tabs to allow for options being stored in multi-level array
- Add `rows` and `cols` option for textarea
- Add `type` option for text

## 1.1.3 - 2023-05-25

- Fix custom validation bug (#11)

## 1.1.2 - 2023-01-28

- Fixing notice

## 1.1.0 - 2023-01-19

- Add priority to action hooks in `make` method
- Option to provide callback for options (select, select-multiple, choices)
- Allow default value for options by @alessandroalessio in [commit](https://github.com/jeffreyvr/wp-settings/commit/0849738b1f6590fccbbeb6c08b3220226b082cf3))
- Add `visible` callback option in [commit](https://github.com/jeffreyvr/wp-settings/commit/252b3038d837e4abe17a94a20c66b6f7294b0078)
- Refactor saving options: now from active tabs
- Use wp nonce instead of regular input on save
- Write url only if parent slug contains `.php`
- Add color option
- Add option to set css classes on option input and label (suggested by @vikasbhvsr)

## 1.0.0 - 2021-08-13

- initial release

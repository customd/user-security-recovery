# [2.1.0](https://github.com/customd/user-security-recovery/compare/v2.0.0...v2.1.0) (2023-03-27)


### Bug Fixes

* validation rule and action ([8ef8e89](https://github.com/customd/user-security-recovery/commit/8ef8e897d6203a8e5e245040659738914628a5dd))


### Features

* laravel 10 support ([44305cd](https://github.com/customd/user-security-recovery/commit/44305cded59d9dc3f9ee4a4d14ee650b1b368cf4))

# [2.0.0](https://github.com/customd/user-security-recovery/compare/v1.1.5...v2.0.0) (2022-08-11)


* Lara 9 (#3) ([45d0b1c](https://github.com/customd/user-security-recovery/commit/45d0b1c13ca22352a2b442a8a4e53d28252093b6)), closes [#3](https://github.com/customd/user-security-recovery/issues/3)


### BREAKING CHANGES

* stops support for older versions

* ci: semver

* feat: disable older php versions
* see upgrade instructions

Co-authored-by: Craig Smith <craig.smith@customd.com>

## [1.1.5](https://github.com/customd/user-security-recovery/compare/v1.1.4...v1.1.5) (2021-11-08)


### Bug Fixes

* allow validate recovery to return or throw an exception ([e2a0fe7](https://github.com/customd/user-security-recovery/commit/e2a0fe7733a2c69af6d67522014102f92cd2b427))

## [1.1.4](https://github.com/customd/user-security-recovery/compare/v1.1.3...v1.1.4) (2021-10-20)


### Bug Fixes

* should throw exception if password not valid ([c824ac2](https://github.com/customd/user-security-recovery/commit/c824ac262b06138f748bc517b33a32228f39548b))

## [1.1.3](https://github.com/customd/user-security-recovery/compare/v1.1.2...v1.1.3) (2021-04-16)


### Bug Fixes

* Encryption salt should be passed as recieved ([12b6eaf](https://github.com/customd/user-security-recovery/commit/12b6eaf618505b7584d06dc0b47b9657639f4291))

## [1.1.2](https://github.com/customd/user-security-recovery/compare/v1.1.1...v1.1.2) (2021-04-16)


### Bug Fixes

* Throw exception if recovery record not found ([0ff1a76](https://github.com/customd/user-security-recovery/commit/0ff1a76c2430fcc2009585e2fa6dc2c1723dc3f2))

## [1.1.1](https://github.com/customd/user-security-recovery/compare/v1.1.0...v1.1.1) (2021-04-16)


### Bug Fixes

* setRecoveryRecord will now return the instance ([cddcf42](https://github.com/customd/user-security-recovery/commit/cddcf42bed4c05e1c89682822697aa34ff721797))

# [1.1.0](https://github.com/customd/user-security-recovery/compare/v1.0.2...v1.1.0) (2021-04-16)


### Features

* Add ability to update existing recovery ([3e17e66](https://github.com/customd/user-security-recovery/commit/3e17e66a7917a933c35d265b02fec47218cad6c8))

## [1.0.2](https://github.com/customd/user-security-recovery/compare/v1.0.1...v1.0.2) (2021-04-15)


### Bug Fixes

* requiresQuestion property should be in the initial trait to allow being overwritten later. ([d75f92f](https://github.com/customd/user-security-recovery/commit/d75f92f27c7a38cf40bc6aed21d29847e2db51b2))

## [1.0.1](https://github.com/customd/user-security-recovery/compare/v1.0.0...v1.0.1) (2021-04-15)


### Bug Fixes

* getRecoveryKey option does not need to re-validate ([12fa000](https://github.com/customd/user-security-recovery/commit/12fa0006137e1fa9f1e7f0fd83523c391a95dd81))

# 1.0.0 (2021-04-15)


### Bug Fixes

* Add settings for question / answer ([e89f5c1](https://github.com/customd/user-security-recovery/commit/e89f5c1ad22926cdfae0040ecbffd380d0ddda80))
* Basic verification update ([5693de1](https://github.com/customd/user-security-recovery/commit/5693de11efba7b371049cb638a3d789152d1dd6e))
* Private key setter and spelling mistake ([64b252e](https://github.com/customd/user-security-recovery/commit/64b252e1eda75c19fec210455deace29541b8a2c))


### Features

* Encrypted version update ([5c4031f](https://github.com/customd/user-security-recovery/commit/5c4031fefff1e76df98b0768cf13967a543026dc))
* Initial Workflow ([a435e82](https://github.com/customd/user-security-recovery/commit/a435e82b0cd61c43517ae0291ef11db39244475a))

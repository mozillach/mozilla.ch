set :application, "Mozilla.ch"
set :domain,      "colinfrei.com"
set :deploy_to,   "/home/colinfre/public_html/mozilla-ch"
set :app_path,    "app"

set :repository,  "git@github.com:mozillacommunitych/colins-mozillach-playground.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set   :deploy_via,       :capifony_copy_local
set   :use_composer,     true
set   :use_composer_tmp, true

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads"]

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set   :use_sudo,      false
set  :keep_releases,  3

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

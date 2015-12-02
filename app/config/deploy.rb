set :application, "Mozilla.ch"
set :domain,      "mozilla.ch"
set :deploy_to,   "/var/www/mozillach" 
set :app_path,    "app"
set :user,        "michael"

set :repository,  "git@github.com:mozillacommunitych/mozilla.ch.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set   :deploy_via,       :capifony_copy_local
set   :use_composer,     true
set   :use_composer_tmp, true

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads"]
set :dump_assetic_assets, true

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set   :use_sudo,      false
set  :keep_releases,  3

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

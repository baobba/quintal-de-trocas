Rails.application.routes.draw do

  devise_for :admin_users, {class_name: 'User'}.merge(ActiveAdmin::Devise.config)
  ActiveAdmin.routes(self)
  # The priority is based upon order of creation: first created -> highest priority.
  # See how all your routes lay out with "rake routes".

  # You can have the root of your site routed with "root"
  root 'toys#index'

  scope(path_names: { new: 'novo', edit: 'alterar' }) do

    devise_for :users, controllers: { sessions: 'sessions', registrations: 'registrations' }, path: 'usuario'

    resources :users, path: 'usuarios' do
      get :netto, on: :collection
    end
    
    resources :articles, path: 'artigos'

    resources :credits, path: 'creditos'
    get 'meus-creditos' => 'credits#my_credits', as: :my_credits

    resources :exchanges, path: 'trocas' do
      # post 'toggle_status', path: 'mudar_status'
      get 'reply', path: 'mudar_status'
      put 'reply_message', path: 'responder', on: :member
    end

    get 'minhas-trocas' => 'exchanges#my_exchanges', as: :my_exchanges

    resources :places, path: 'pontos'
    get 'meus-pontos' => 'places#my_places', as: :my_places

    resources :toys, path: 'brinquedos' do
      get 'index_near', on: :collection
      get 'exchange', on: :member
      get 'activate', on: :member
    end

    get 'meus-brinquedos' => 'toys#my_toys', as: :my_toys

    resources :toy_ages, path: 'faixa-etaria'
    resources :toy_categories, path: 'categorias'
    resources :toy_images, path: 'imagens'

    resources :conversations, only: [:index, :show, :new, :create] do
      member do
        post :reply
        post :trash
        post :untrash
      end
    end
  end

  resources :contacts, only: [:new, :create]
  post 'contact', to: 'messages#create'

  get 'sobre-nos' => 'pages#about_us', as: 'about_us'
  get 'faq' => 'pages#faq', as: 'faq'
  get 'fale_conosco' => 'pages#contact_us', as: 'contact_us'
  get 'apoie' => 'pages#support', as: 'support'
  get 'como-funciona' => 'pages#how_it_works', as: 'how_it_works'
  get 'depoimentos' => 'pages#testimonials', as: 'testimonials'
  get 'parceiros' => 'pages#partners', as: 'partners'
  get 'na-midia' => 'pages#media', as: 'media'
  get 'busca-por-cep' => 'pages#busca_por_cep'
  get 'politica-de-privacidade' => 'pages#privacy', as: 'privacy'
  get 'termos-e-condicoes' => 'pages#terms', as: 'terms'
  
  match "/404", :to => "errors#not_found", :via => :all
  match "/500", :to => "errors#internal_server_error", :via => :all


  # 301 redirects
  
  get('/produtos/detalhe/:id', to: redirect do |params, request|
    Rails.application.routes.url_helpers.toy_path(params[:id])
  end)

  get('/colunas_novidades/detalhe/:id', to: redirect do |params, request|
    Rails.application.routes.url_helpers.article_path(params[:id])
  end)
  # get '/produtos/detalhe/:id', to: redirect("/brinquedos/%{id}", status: 301)
  
  get '/na_midia', to: redirect("/na-midia", status: 301)
  get '/como_funciona', to: redirect("/como-funciona", status: 301)
  get '/pontos_de_trocas', to: redirect("/pontos", status: 301)
  get '/produtos/listar', to: redirect("/brinquedos", status: 301)
  get '/quem_somos', to: redirect("/sobre-nos", status: 301)

  match '/get_mailgun_data' => 'home#get_mailgun_data', :via => :all

end

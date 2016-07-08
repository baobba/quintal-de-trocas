Rails.application.routes.draw do
  
  ActiveAdmin.routes(self)
  # The priority is based upon order of creation: first created -> highest priority.
  # See how all your routes lay out with "rake routes".

  devise_for :admin_users, ActiveAdmin::Devise.config
  
  # You can have the root of your site routed with "root"
  root 'toys#index'

  scope(path_names: { new: 'novo', edit: 'alterar' }) do
    
    devise_for :users, controllers: {registrations: 'registrations'}, path: 'usuario'
    
    resources :articles
    
    resources :exchanges, path: 'trocas'
    get 'minhas-trocas' => 'exchanges#my_exchanges', as: :my_exchanges

    resources :places, path: 'pontos'
    get 'meus-pontos' => 'places#my_places', as: :my_places

    resources :toys, path: 'brinquedos'
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

  get 'sobre-nos' => 'pages#about_us', as: 'about_us'
  get 'apoio' => 'pages#support', as: 'support'
  get 'como-funciona' => 'pages#how_it_works', as: 'how_it_works'
  get 'depoimentos' => 'pages#testimonials', as: 'testimonials'
  get 'parceiros' => 'pages#partners', as: 'partners'
  get 'na-midia' => 'pages#media', as: 'media'
  get 'busca-por-cep' => 'pages#busca_por_cep'

  # Example of regular route:
  #   get 'products/:id' => 'catalog#view'

  # Example of named route that can be invoked with purchase_url(id: product.id)
  #   get 'products/:id/purchase' => 'catalog#purchase', as: :purchase

  # Example resource route (maps HTTP verbs to controller actions automatically):
  #   resources :products

  # Example resource route with options:
  #   resources :products do
  #     member do
  #       get 'short'
  #       post 'toggle'
  #     end
  #
  #     collection do
  #       get 'sold'
  #     end
  #   end

  # Example resource route with sub-resources:
  #   resources :products do
  #     resources :comments, :sales
  #     resource :seller
  #   end

  # Example resource route with more complex sub-resources:
  #   resources :products do
  #     resources :comments
  #     resources :sales do
  #       get 'recent', on: :collection
  #     end
  #   end

  # Example resource route with concerns:
  #   concern :toggleable do
  #     post 'toggle'
  #   end
  #   resources :posts, concerns: :toggleable
  #   resources :photos, concerns: :toggleable

  # Example resource route within a namespace:
  #   namespace :admin do
  #     # Directs /admin/products/* to Admin::ProductsController
  #     # (app/controllers/admin/products_controller.rb)
  #     resources :products
  #   end
end

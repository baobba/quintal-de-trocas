require 'test_helper'

class ToyAgesControllerTest < ActionController::TestCase
  setup do
    @toy_age = toy_ages(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:toy_ages)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create toy_age" do
    assert_difference('ToyAge.count') do
      post :create, toy_age: { title: @toy_age.title }
    end

    assert_redirected_to toy_age_path(assigns(:toy_age))
  end

  test "should show toy_age" do
    get :show, id: @toy_age
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @toy_age
    assert_response :success
  end

  test "should update toy_age" do
    patch :update, id: @toy_age, toy_age: { title: @toy_age.title }
    assert_redirected_to toy_age_path(assigns(:toy_age))
  end

  test "should destroy toy_age" do
    assert_difference('ToyAge.count', -1) do
      delete :destroy, id: @toy_age
    end

    assert_redirected_to toy_ages_path
  end
end

/*
Main Angular app for hrms restful API
*/
var hrmsApp = angular.module('hrmsApp', ['ngRoute','ngTable', 'ngResource']);
  
hrmsApp.config(
    function($routeProvider) {
    $routeProvider.
        when('/',
            {controller: 'profileCtl',templateUrl: 'templates/dashboard.html'}
        ).
        when('/profile',
            {controller: 'profileCtl',templateUrl: 'templates/profile.html'}
        ).
        when('/documents',
            {controller: 'documentCtl',templateUrl: 'templates/documents.html'}
        ).
        when('/qualifications',
            {controller: 'qualificationCtl',templateUrl: 'templates/qualification.html'}
        ).
        when('/experience',
            {controller: 'experienceCtl',templateUrl: 'templates/experience.html'}
        ).
        when('/visa',
            {controller: 'visaCtl',templateUrl: 'templates/visa.html'}
        ).
        when('/projects',
            {controller: 'projectCtl',templateUrl: 'templates/projects.html'}
        ).
        when('/team_members',
            {controller: 'profileCtl',templateUrl: 'templates/team_members.html'}
        ).
        when('/circulars',
            {controller: 'profileCtl',templateUrl: 'templates/circulars.html'}
        ).
        when('/apply_leave',
            {controller: 'profileCtl',templateUrl: 'templates/apply_leave.html'}
        ).
        when('/leaves',
            {controller: 'profileCtl',templateUrl: 'templates/leaves.html'}
        ).
        when('/team_leaves',
            {controller: 'profileCtl',templateUrl: 'templates/team_leaves.html'}
        );
    }
);
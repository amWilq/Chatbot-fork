import { Component } from '@angular/core';

@Component({
  selector: 'home-page-component',
  templateUrl: 'home-page-component.html',
  styleUrls: ['home-page-component.scss']
})
export class HomePageComponent {
  sampleCardData: any[] = [];
  constructor() { }

  ngOnInit() {

    this.sampleCardData = [
      {
        title: 'Angular',
        src: '/assets/images/angular.png'
      },
      {
        title: 'Ionic',
        src: '/assets/images/ionic.svg'
      },
      {
        title: 'Scss',
        src: '/assets/images/scss.svg'
      },
      {
        title: 'HTML',
        src: '/assets/images/html.png'
      }
    ];



  }

}

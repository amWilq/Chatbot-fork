import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'home-page-component',
  templateUrl: 'home-page-component.html',
  styleUrls: ['home-page-component.scss']
})
export class HomePageComponent {
  sampleCardData: any[] = [];
  isClicked: boolean = false;
  selectedCategory!: string;
  constructor(private router: Router) { }


  ngOnInit() {
    const sampleCardData = [
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

    localStorage.setItem('favorites-category', JSON.stringify(sampleCardData));
    this.sampleCardData = JSON.parse(localStorage.getItem('favorites-category') || '[]');
    if (this.sampleCardData.length === 0) {
      return;
    }
    console.log(this.sampleCardData);
  }

  handleNewCardEvent(data: any,componentIdentifier: string) {
    this.selectedCategory = componentIdentifier;
    this.isClicked = data;

  }

  goToCategoryView(e: Event) {
    this.router.navigate(['tabs/tab2'], {
      queryParams: {
        selectedCategory: this.selectedCategory,
      }
    });
  }
}

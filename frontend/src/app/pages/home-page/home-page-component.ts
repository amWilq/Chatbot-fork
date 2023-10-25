import { StackedCardComponent } from './../../components/stacked-card/stacked-card-component';
import { Component, ElementRef, ViewChild } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'home-page-component',
  templateUrl: 'home-page-component.html',
  styleUrls: ['home-page-component.scss']
})
export class HomePageComponent {
  sampleCardData: any[] = [];
  isClicked: boolean = false;
  selectedCategory!: any;
  selectedCard: any = null; // Initialize selectedCard as null
  isCardVisible = true;
  placeholder!: string;
  imageSrc!: string;
  selectedCardIndex: number | null = null;

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

  handleNewCardEvent(index: any) {
    if (this.selectedCardIndex === index) {
      this.selectedCardIndex = -1;
    } else {
      this.selectedCardIndex = index;
    }

    this.isClicked = this.selectedCardIndex !== -1;
    this.selectedCategory = this.isClicked ? this.sampleCardData[index] : null;

    if (this.selectedCategory) {
      console.log('Zawartość klikniętej karty:', this.selectedCategory, index);
    }
  }


  toggleCardVisibility() {
    this.isCardVisible = !this.isCardVisible;
  }

  handleArrowIconClick(navigateTo: string) {
    this.router.navigate(['/tabs/' + navigateTo]);
  }



  isCardSelected(): boolean {
    return this.selectedCard === this.placeholder;
  }


  goToCategoryView(e: Event) {
    this.router.navigate(['tabs/tab2'], {
      queryParams: {
        selectedCategory: this.selectedCategory,
      }
    });
  }
}

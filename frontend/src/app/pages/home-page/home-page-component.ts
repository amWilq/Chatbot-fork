import { Component, EventEmitter, Output } from '@angular/core';
import { Router } from '@angular/router';

interface Category {
  title: string;
  src: string;
}

@Component({
  selector: 'home-page-component',
  templateUrl: 'home-page-component.html',
  styleUrls: ['home-page-component.scss']
})
export class HomePageComponent {
  @Output() newItemEvent = new EventEmitter<string>();

  isClicked: boolean = false;
  selectedCategory: Category | null = null;
  selectedCard: Category | null = null;
  isCardVisible = true;
  placeholder: string | undefined;
  imageSrc: string | undefined;
  selectedCardIndex: number | null = null;
  sampleCategoryData: Category[] = [];
  sampleFavData: Category[] = [];
  selectedFav: Category | null = null;

  selectedFavCardIndex: number | null = null;
  selectedCategoryCardIndex: number | null = null;

  constructor(private router: Router) {}

  ngOnInit() {
    this.loadSimpleData();
  }

  loadSimpleData() {
    const sampleFavData: Category[] = [
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
    const sampleCategoryData: Category[] = [
      {
        title: 'Frontend',
        src: '/assets/images/angular.png'
      },
      {
        title: 'Backend',
        src: '/assets/images/ionic.svg'
      },
      {
        title: 'Cybersecurity',
        src: '/assets/images/scss.svg'
      },
      {
        title: 'Other',
        src: '/assets/images/html.png'
      }
    ];

    localStorage.setItem('favorites-category', JSON.stringify(sampleFavData));
    this.sampleFavData = JSON.parse(localStorage.getItem('favorites-category') || '[]');
    if (this.sampleFavData.length === 0) {
      return;
    }

    localStorage.setItem('category-data', JSON.stringify(sampleCategoryData));
    this.sampleCategoryData = JSON.parse(localStorage.getItem('category-data') || '[]');
    if (this.sampleCategoryData.length === 0) {
      return;
    }
  }


  handleNewCardEvent(card: Category, index: number, isFavData: boolean) {
    if (isFavData) {
      if (this.selectedFavCardIndex === index) {
        this.selectedFavCardIndex = null;
        if (this.selectedCategoryCardIndex === index) {
          this.selectedCategoryCardIndex = null;
        }
      } else {
        if (this.selectedFavCardIndex !== null) {
          this.selectedFavCardIndex = null;
        }
        this.selectedFavCardIndex = index;
        if (this.selectedCategoryCardIndex === index) {
          this.selectedCategoryCardIndex = null;
        }
      }
    } else {
      if (this.selectedCategoryCardIndex === index) {
        this.selectedCategoryCardIndex = null;

        if (this.selectedFavCardIndex === index) {
          this.selectedFavCardIndex = null;
        }
      } else {
        if (this.selectedCategoryCardIndex !== null) {
          this.selectedCategoryCardIndex = null;
        }
        this.selectedCategoryCardIndex = index;
        if (this.selectedFavCardIndex === index) {
          this.selectedFavCardIndex = null;
        }
      }
    }
  }

  deleteCard(card: Category) {
    const index = this.sampleFavData.indexOf(card);
    if (index >= 0) {
      this.sampleFavData.splice(index, 1);
      localStorage.setItem('favorites-category', JSON.stringify(this.sampleFavData));
    }
  }

  toggleCardVisibility() {
    this.isCardVisible = !this.isCardVisible;
  }

  handleArrowIconClick(navigateTo: string) {
    this.router.navigate(['/tabs/' + navigateTo], { queryParams: { disabled: true } });
  }

  isCardSelected(): boolean {
    return this.selectedCard !== null && this.selectedCard.title === this.placeholder;
  }


  goToCategoryView(e: Event) {
    this.router.navigate(['tabs/tab2'], {
      queryParams: {
        selectedCategory: this.selectedCategory,
        disabled: true
      }
    });
  }
}

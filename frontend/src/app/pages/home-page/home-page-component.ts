import { HttpResponse } from '@angular/common/http';
import { Component, EventEmitter, Output } from '@angular/core';
import { Router } from '@angular/router';
import { Category } from 'src/app/entities/category.model';
import { CategoryService } from 'src/app/services/category.service';

// interface Category {
//   title: string;
//   src: string;
// }

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
  categorys: Category[] = [];

  constructor(
    private router: Router,
    private categoryService: CategoryService,

  ) { }

  ngOnInit() {
    this.loadSimpleData();
  }

  loadSimpleData() {

    this.categoryService.getAllCategories().subscribe(
      (res: HttpResponse<any>) => {
        this.categorys = res.body.items ?? [];
        console.log(this.categorys);
      },
      error => {
        console.error('Error fetching categories:', error);
      }
    );


    const sampleFavData: Category[] = [
      {
        name: 'Angular',
        // src: '/assets/images/angular.png'
      },
      {
        name: 'Ionic',
        // src: '/assets/images/ionic.svg'
      },
      {
        name: 'Scss',
        // src: '/assets/images/scss.svg'
      },
      {
        name: 'HTML',
        // src: '/assets/images/html.png'
      }
    ];

    localStorage.setItem('favorites-category', JSON.stringify(sampleFavData));
    this.sampleFavData = JSON.parse(localStorage.getItem('favorites-category') || '[]');
    if (this.sampleFavData.length === 0) {
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

  handleArrowIconClick(navigateTo: string, card: Category) {
    this.router.navigate(['/tabs/' + navigateTo],
      {
        queryParams: {
          disabled: true,
          selectedCategory: card.categoryId
        }
      });
  }

  isCardSelected(): boolean {
    return this.selectedCard !== null && this.selectedCard.name === this.placeholder;
  }


  goToCategoryView(e: Event) {
    console.log('goToCategoryView', this.selectedCategory);
    this.router.navigate(['tabs/tab2'], {
      queryParams: {
        selectedCategory: this.selectedCategory,
        disabled: true
      }
    });
  }
}

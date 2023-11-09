import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'category-component',
  templateUrl: 'category-component.html',
  styleUrls: ['category-component.scss']
})
export class CategoryComponent implements OnInit {
  selectedCategory: string | null = null;
  selectedItemId: string | null = null;
  items: string[] = ['Angular 1', 'test 2', 'srest 3', 'dupa 4', 'Item 5', 'Item 6', 'Item 7'
  , 'Item 8', 'Item 9', 'Item 10', 'Item 11', 'Item 12', 'Item 13', 'Item 14', 'Item 15'];
  filteredItems: string[] = this.items;
  constructor(
    private activatedRoute: ActivatedRoute,
    private router: Router
  ) { }

  ngOnInit() {
    this.activatedRoute.queryParams.subscribe((params) => {
      this.selectedCategory = params['selectedCategory'];
    });
  }

  itemClicked(itemName: string) {
    this.selectedItemId = itemName;
  }


  searchItems(event: Event) {
    const searchText = (event.target as HTMLInputElement).value.toLowerCase();
    if (!searchText) {
      this.filteredItems = this.items;
    } else {
      this.filteredItems = this.items.filter(item => item.toLowerCase().includes(searchText));
    }
  }

  startQuiz(e: Event) {
    this.router.navigate(['tabs/tab3'], {
      queryParams: {
        selectedCategory: this.selectedCategory,
        disabled: true
      }
    });
  }
}

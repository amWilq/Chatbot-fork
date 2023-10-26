import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CustomMatCardComponent } from './custom-mat-card.component';

describe('CustomMatCardComponent', () => {
  let component: CustomMatCardComponent;
  let fixture: ComponentFixture<CustomMatCardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [CustomMatCardComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CustomMatCardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
